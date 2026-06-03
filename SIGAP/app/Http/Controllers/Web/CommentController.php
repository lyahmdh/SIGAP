<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * POST /laporan/{id}/komentar
     * Tambah komentar pada laporan. User harus login.
     */
    public function store(StoreCommentRequest $request, string $id)
    {
        $validated = $request->validated();

        Comment::create([
            'report_id' => $id,
            'user_id'   => Auth::id(),
            'comment'   => $validated['comment'],
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * DELETE /komentar/{id}
     * Hapus komentar milik sendiri.
     */
    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        // Simpan report_id sebelum dihapus untuk redirect
        $reportId = $comment->report_id;

        $comment->delete();

        return redirect()
            ->route('laporan.show', $reportId)
            ->with('success', 'Komentar berhasil dihapus.');
    }
}
