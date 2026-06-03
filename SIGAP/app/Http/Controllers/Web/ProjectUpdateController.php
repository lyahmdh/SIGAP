<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectUpdate\StoreProjectUpdateRequest;
use App\Models\ProjectUpdate;
use App\Models\ProjectUpdateImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
