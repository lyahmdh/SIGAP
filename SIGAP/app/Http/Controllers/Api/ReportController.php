<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportRequest;
use App\Models\Category;
use App\Models\Report;
use App\Models\ReportImage;
use App\Mail\ReportStatusUpdatedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::query()

            // hanya ambil field yang dibutuhkan
            ->select('id', 'title', 'category_id', 'status')

            // relasi minimal
            ->with(['category:id,name']);

        $user = auth()->user();

        // Riwayat Laporan
        if ($request->boolean('mine') && $user) {
            $query->where('user_id', $user->id);
        }

        // HIDE DITOLAK (publik)
        if (
            !$request->boolean('include_rejected') &&
            !($user && $user->role === 'admin') &&
            !$request->boolean('mine')
        ) {
            $query->where('status', '!=', 'ditolak');
        }

        // SEARCH
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        // SORTING
        switch ($request->sort) {
            case 'priority':
                $query->orderByDesc('priority_score');
                break;

            case 'oldest':
                $query->orderBy('created_at');
                break;

            default:
                $query->latest();
                break;
        }

        $reports = $query->get();

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    public function show(string $id)
    {
        $report = Report::with([
            'user',
            'category',
            'images',
            'comments.user',
            'projectUpdates.images'
        ])->find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report tidak ditemukan'
            ], 404);
        }

        // tidak tampil kalau ditolak
        if ($report->status === 'ditolak') {
            return response()->json([
                'success' => false,
                'message' => 'Report tidak ditemukan'
            ], 404);
        }

        // anonim
        if ($report->is_anonymous && $report->user) {
            $report->user->name = 'Anonim';
        }

        // format alamat
        $report->formatted_address =
            "Kelurahan {$report->subdistrict}, Kecamatan {$report->district}";

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();

        // duplicate detection (30 meter)
        $duplicate = Report::where('user_id', auth()->id())
            ->where('category_id', $validated['category_id'])
            ->whereRaw("
                ST_Distance_Sphere(
                    POINT(longitude, latitude),
                    POINT(?, ?)
                ) <= ?
            ", [
                $validated['longitude'],
                $validated['latitude'],
                30
            ])
            ->exists();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melaporkan lokasi ini'
            ], 422);
        }

        $category = Category::find($validated['category_id']);

        // report count berdasarkan radius kategori
        $reportCount = Report::where('category_id', $category->id)
            ->whereRaw("
                ST_Distance_Sphere(
                    POINT(longitude, latitude),
                    POINT(?, ?)
                ) <= ?
            ", [
                $validated['longitude'],
                $validated['latitude'],
                $category->report_radius
            ])
            ->distinct('user_id')
            ->count('user_id');

        // severity score
        $severityScore = match ((int) $validated['severity']) {
            1 => 30,
            2 => 60,
            3 => 100,
        };

        // report count score
        $reportCountScore =
            min($reportCount, 10) * 10;

        // waiting score awal
        $waitingScore = 0;

        // final priority score
        $priorityScore =
            ($severityScore * 0.3) +
            ($reportCountScore * 0.5) +
            ($waitingScore * 0.2);

        DB::beginTransaction();

        try {

            $report = Report::create([
                'user_id' => auth()->id(),
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'severity' => $validated['severity'],
                'is_anonymous' => $validated['is_anonymous'],
                'status' => 'masuk',
                'location_name' => $validated['location_name'],
                'district' => $validated['district'],
                'subdistrict' => $validated['subdistrict'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'priority_score' => round($priorityScore, 2)
            ]);

            foreach ($validated['images'] as $image) {

                $path = $image->store(
                    'reports',
                    'public'
                );

                ReportImage::create([
                    'report_id' => $report->id,
                    'image_path' => $path
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dibuat',
                'data' => $report->load([
                    'images',
                    'category'
                ])
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report tidak ditemukan'
            ], 404);
        }

        $user = auth()->user();

        // hanya pemilik laporan
        if ($report->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // hanya jika belum diverifikasi
        if ($report->status !== 'masuk') {
            return response()->json([
                'success' => false,
                'message' => 'Laporan hanya bisa dihapus sebelum diverifikasi'
            ], 422);
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report tidak ditemukan'
            ], 404);
        }

        // hanya admin
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'status' => [
                'required',
                'in:diverifikasi,ditindaklanjuti,selesai,ditolak'
            ]
        ]);

        // Status flow (tidak boleh mundur)
        $allowedTransitions = [
            'masuk' => ['diverifikasi', 'ditolak'],
            'diverifikasi' => ['ditindaklanjuti', 'ditolak'],
            'ditindaklanjuti' => ['selesai'],
            'selesai' => [],
            'ditolak' => []
        ];

        if (!in_array($request->status, $allowedTransitions[$report->status])) {
            return response()->json([
                'success' => false,
                'message' => 'Transisi status tidak valid'
            ], 422);
        }

        $report->update([
            'status' => $request->status
        ]);

        // kirim email
        try {
            Mail::to($report->user->email)
                ->send(new ReportStatusUpdatedMail($report));
        } catch (\Exception $e) {
            \Log::error('Email gagal: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui',
            'data' => $report
        ]);
    }
}