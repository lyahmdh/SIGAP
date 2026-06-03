<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * GET /profile
     * Halaman profil user + ringkasan riwayat laporan.
     */
    public function show()
    {
        $user = Auth::user();

        // ← load reports terpisah untuk tabel (paginate), jangan pakai eager load limit
        $reports = $user->reports()
            ->with('category:id,name')
            ->latest()
            ->paginate(10);

        $reportSummary = [
            'total'    => $user->reports()->count(),
            'selesai'  => $user->reports()->where('status', 'selesai')->count(),
            'diproses' => $user->reports()->whereIn('status', ['diverifikasi', 'ditindaklanjuti'])->count(),
            'masuk'    => $user->reports()->where('status', 'masuk')->count(),
        ];

        return view('profile.show', compact('user', 'reports', 'reportSummary'));
        //                                           ↑ wajib di-pass
    }

    /**
     * GET /profile/edit
     * Form edit profil.
     */
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * PUT /profile
     * Simpan perubahan profil (nama, email, foto).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Ganti foto profil jika ada upload baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('photo')->store('profiles', 'public');
            $user->profile_photo = $path;
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * PUT /profile/password
     * Ganti password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update(['password' => $request->password]); // di-hash otomatis

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
