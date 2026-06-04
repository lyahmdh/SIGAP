<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectUpdate\StoreProjectUpdateRequest;
use App\Models\ProjectUpdate;
use App\Models\ProjectUpdateImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectUpdateController extends Controller
{
    /**
     * POST /admin/laporan/{id}/update
     * Admin menambahkan update progres proyek.
     */
    public function store(StoreProjectUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $update = ProjectUpdate::create([
                'report_id'   => $id,
                'admin_id'    => Auth::id(),
                'title'       => $validated['title'],
                'description' => $validated['description'],
            ]);

            if (!empty($validated['images'])) {
                foreach ($validated['images'] as $image) {
                    $path = $image->store('project-updates', 'public');
                    ProjectUpdateImage::create([
                        'project_update_id' => $update->id,
                        'image_path'        => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.laporan.show', $id)
                ->with('success', 'Update proyek berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan update proyek. Silakan coba lagi.');
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $update = ProjectUpdate::with('images')->findOrFail($id);

            // Hapus file gambar
            foreach ($update->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Hapus record gambar
            $update->images()->delete();

            // Hapus update
            $reportId = $update->report_id;
            $update->delete();

            DB::commit();

            return redirect()
                ->route('admin.laporan.show', $reportId)
                ->with('success', 'Update proyek berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Gagal menghapus update proyek.');
        }
    }
}
