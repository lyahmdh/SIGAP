<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportRequest;
use App\Models\Category;
use App\Models\Report;
use App\Models\ReportImage;
use App\Mail\ReportStatusUpdatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    //  PUBLIC – Rekap / Semua Proyek
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /rekap
     * Daftar laporan publik dengan filter, search, sort, dan pagination.
     */
    public function index(Request $request)
    {
        $query = Report::query()
            ->select([
                'id', 'title', 'category_id', 'status',
                'priority_score', 'district', 'created_at', 'is_anonymous', 'user_id',
            ])
            ->with(['category:id,name', 'images:id,report_id,image_path'])
            ->where('status', '!=', 'ditolak'); // publik tidak melihat yang ditolak

        // ── SEARCH ──────────────────────────────────────
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // ── FILTER ──────────────────────────────────────
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            // Filter by category name (user-facing)
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->kategori);
            });
        }

        if ($request->filled('kecamatan')) {
            $query->where('district', $request->kecamatan);
        }

        // ── SORT ─────────────────────────────────────────
        match ($request->sort) {
            'priority' => $query->orderByDesc('priority_score'),
            'oldest'   => $query->orderBy('created_at'),
            default    => $query->latest(),
        };

        // ── PAGINATE ─────────────────────────────────────
        $reports = $query->paginate(12)->withQueryString();

        // ── SIDEBAR DATA ──────────────────────────────────
        // Jumlah laporan per kecamatan (untuk strip atas)
        $districtStats = Report::query()
            ->where('status', '!=', 'ditolak')
            ->selectRaw('district as name, COUNT(*) as count')
            ->groupBy('district')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Daftar kategori untuk dropdown filter
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        return view('semua-proyek', compact(
            'reports',
            'districtStats',
            'categories',
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  PUBLIC – Detail Laporan
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /laporan/{id}
     */
    public function show(string $id)
    {
        $report = Report::with([
            'user:id,name,profile_photo',
            'category:id,name',
            'images:id,report_id,image_path',
            'comments.user:id,name,profile_photo',
            'projectUpdates' => fn ($q) => $q->oldest(),
            'projectUpdates.images:id,project_update_id,image_path',
        ])->find($id);

        if (!$report || $report->status === 'ditolak') {
            abort(404);
        }

        // Anonimkan nama pelapor
        $report->display_name = $report->is_anonymous
            ? 'Anonim'
            : $report->user->name;

        $report->formatted_address =
            "Kecamatan {$report->district}";
        
        return view('laporan.show', compact('report'));
    }

    // ─────────────────────────────────────────────────────────────
    //  USER – Form Buat Laporan
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /lapor
     */
    public function create()
    {
        $categories = Category::select('id', 'name', 'report_radius')
            ->orderBy('name')
            ->get();

        return view('laporan.create', compact('categories'));
    }

    /**
     * POST /lapor
     */
    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();

        // ── Cek duplikat (30 meter, sama user & kategori) ────────
        $duplicate = Report::where('user_id', Auth::id())
            ->where('category_id', $validated['category_id'])
            ->whereRaw(
                'ST_Distance_Sphere(POINT(longitude, latitude), POINT(?, ?)) <= ?',
                [$validated['longitude'], $validated['latitude'], 30]
            )
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors(['location' => 'Anda sudah pernah melaporkan lokasi ini.']);
        }

        $category = Category::findOrFail($validated['category_id']);

        // ── Hitung report count di radius kategori ───────────────
        $reportCount = Report::where('category_id', $category->id)
            ->whereRaw(
                'ST_Distance_Sphere(POINT(longitude, latitude), POINT(?, ?)) <= ?',
                [$validated['longitude'], $validated['latitude'], $category->report_radius]
            )
            ->distinct('user_id')
            ->count('user_id');

        // ── Hitung priority score ────────────────────────────────
        $severityScore    = match ((int) $validated['severity']) {
            1 => 30,
            2 => 60,
            3 => 100,
        };
        $reportCountScore = min($reportCount, 10) * 10;
        $waitingScore     = 0; // baru dibuat, belum menunggu
        $priorityScore    = ($severityScore * 0.3) + ($reportCountScore * 0.5) + ($waitingScore * 0.2);

        // ── Simpan ke DB ─────────────────────────────────────────
        DB::beginTransaction();

        try {
            $report = Report::create([
                'user_id'       => Auth::id(),
                'category_id'   => $validated['category_id'],
                'title'         => $validated['title'],
                'description'   => $validated['description'],
                'severity'      => $validated['severity'],
                'is_anonymous'  => $validated['is_anonymous'] ?? false,
                'status'        => 'masuk',
                'location_detail' => $validated['location_detail'],
                'district'      => $validated['district'],
                'latitude'      => $validated['latitude'],
                'longitude'     => $validated['longitude'],
                'priority_score'=> round($priorityScore, 2),
            ]);

            foreach ($validated['images'] as $image) {
                $path = $image->store('reports', 'public');
                ReportImage::create([
                    'report_id'  => $report->id,
                    'image_path' => $path,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('laporan.show', $report->id)
                ->with('success', 'Laporan berhasil dikirim. Kami akan segera meninjau laporan Anda.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal membuat laporan', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withInput()
                ->with('error', 'Gagal membuat laporan. Silakan coba lagi.');
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  USER – Riwayat Laporan Pribadi
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /profile/riwayat
     */
    public function myReports(Request $request)
    {
        $query = Report::where('user_id', Auth::id())
            ->with(['category:id,name', 'images:id,report_id,image_path'])
            ->latest();

        // Filter status (opsional dari query param)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(10)->withQueryString();

        return view('laporan.riwayat', compact('reports'));
    }

    public function filter(Request $request)
    {
        $reports = Report::where('user_id', Auth::id())
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->with('category')
            ->get();

        return response()->json($reports);
    }

    // ─────────────────────────────────────────────────────────────
    //  USER – Hapus Laporan Sendiri
    // ─────────────────────────────────────────────────────────────

    /**
     * DELETE /laporan/{id}
     */
    public function destroy(string $id)
    {
        $report = Report::findOrFail($id);

        // Hanya pemilik laporan
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya jika masih berstatus "masuk"
        if ($report->status !== 'masuk') {
            return back()->with(
                'error',
                'Laporan hanya dapat dihapus sebelum diverifikasi.'
            );
        }

        // Hapus gambar dari storage
        foreach ($report->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $report->delete();

        return redirect()
            ->route('profile')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
