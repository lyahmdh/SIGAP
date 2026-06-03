<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\ReportStatusUpdatedMail;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    //  DASHBOARD
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /admin
     * Dashboard statistik + daftar laporan terbaru.
     */
    public function dashboard()
    {
        $stats = [
            'total'          => Report::count(),
            'masuk'          => Report::where('status', 'masuk')->count(),
            'diverifikasi'   => Report::where('status', 'diverifikasi')->count(),
            'ditindaklanjuti'=> Report::where('status', 'ditindaklanjuti')->count(),
            'selesai'        => Report::where('status', 'selesai')->count(),
            'ditolak'        => Report::where('status', 'ditolak')->count(),
        ];

        // 7 laporan terbaru untuk preview tabel
        $recentReports = Report::with('category:id,name')
            ->select('id', 'title', 'category_id', 'status', 'priority_score', 'district', 'created_at')
            ->latest()
            ->limit(7)
            ->get();

        // Data pie chart per kategori
        $byCategory = Report::join('categories', 'reports.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(*) as total')
            ->groupBy('categories.name')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentReports', 'byCategory'));
    }

    // ─────────────────────────────────────────────────────────────
    //  KELOLA LAPORAN
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /admin/laporan
     * Semua laporan termasuk yang ditolak, dengan filter & sort.
     */
    public function laporanIndex(Request $request)
    {
        $query = Report::with(['category:id,name'])
            ->select([
                'id', 'title', 'category_id', 'status',
                'priority_score', 'district', 'created_at',
            ]);

        // ── SEARCH ──────────────────────────────────────
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // ── FILTER ──────────────────────────────────────
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->whereHas('category', fn ($q) => $q->where('name', $request->kategori));
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

        $reports    = $query->paginate(15)->withQueryString();
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        // Kecamatan unik untuk dropdown filter
        $districts = Report::distinct()->pluck('district')->sort()->values();

        return view('admin.laporan.index', compact(
            'reports',
            'categories',
            'districts',
        ));
    }

    /**
     * GET /admin/laporan/{id}
     * Detail laporan (admin view).
     */
    public function laporanShow(string $id)
    {
        $report = Report::with([
            'user:id,name,email,profile_photo',
            'category:id,name',
            'images:id,report_id,image_path',
            'comments.user:id,name,profile_photo',
            'projectUpdates' => fn ($q) => $q->latest(),
            'projectUpdates.images:id,project_update_id,image_path',
        ])->findOrFail($id);

        $report->formatted_address =
            "Kecamatan {$report->district}";

        return view('laporan.show', compact('report'));
    }

    // ─────────────────────────────────────────────────────────────
    //  UPDATE STATUS
    // ─────────────────────────────────────────────────────────────

    /**
     * PATCH /admin/laporan/{id}/status
     * Verifikasi, tolak, atau update progress laporan.
     */
    public function updateStatus(Request $request, string $id)
    {
        $report = Report::findOrFail($id);

        $request->validate([
            'status' => [
                'required',
                'in:diverifikasi,ditindaklanjuti,selesai,ditolak',
            ],
        ]);

        // ── Validasi alur status (tidak boleh mundur) ────────────
        $allowedTransitions = [
            'masuk'           => ['diverifikasi', 'ditolak'],
            'diverifikasi'    => ['ditindaklanjuti', 'ditolak'],
            'ditindaklanjuti' => ['selesai'],
            'selesai'         => [],
            'ditolak'         => [],
        ];

        if (!in_array($request->status, $allowedTransitions[$report->status] ?? [])) {
            return back()->with('error', 'Transisi status tidak valid.');
        }

        $report->update(['status' => $request->status]);

        // ── Kirim email notifikasi ───────────────────────────────
        try {
            if ($report->user?->email) {
                Mail::to($report->user->email)
                    ->send(new ReportStatusUpdatedMail($report));
            }
        } catch (\Exception $e) {
            \Log::error('Email notifikasi gagal dikirim: ' . $e->getMessage());
        }

        return back()->with(
            'success',
            'Status laporan berhasil diperbarui menjadi "' . $request->status . '".'
        );
    }

    // ─────────────────────────────────────────────────────────────
    //  HAPUS LAPORAN DUPLIKAT
    // ─────────────────────────────────────────────────────────────

    /**
     * DELETE /admin/laporan/{id}
     */
    public function laporanDestroy(string $id)
    {
        $report = Report::findOrFail($id);

        // Hapus semua gambar terkait dari storage
        foreach ($report->images as $image) {
            \Storage::disk('public')->delete($image->image_path);
        }

        $report->delete();

        return redirect()
            ->route('admin.laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────────
    //  STATISTIK (JSON – bisa dipanggil lewat fetch di Blade)
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /admin/statistik
     * Mengembalikan data statistik untuk chart/widget.
     */
    public function statistik()
    {
        $data = [
            'total_reports' => Report::count(),

            'by_status' => Report::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->get(),

            'by_category' => Report::join('categories', 'reports.category_id', '=', 'categories.id')
                ->selectRaw('categories.name, COUNT(*) as total')
                ->groupBy('categories.name')
                ->get(),

            'by_district' => Report::selectRaw('district, COUNT(*) as total')
                ->groupBy('district')
                ->orderByDesc('total')
                ->get(),
        ];

        return response()->json($data);
    }
}
