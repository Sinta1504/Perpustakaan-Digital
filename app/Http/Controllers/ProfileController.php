<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\Ticket; // Pastikan Model Ticket diimport
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * --- FITUR ADMIN: PELAYANAN PENGGUNA ---
     */

    /**
     * Menampilkan daftar pengguna untuk Admin.
     */
    public function manageUsers(): View
    {
        // Ambil semua pengguna yang bukan admin
        $users = User::where('role', '!=', 'admin')->get(); 
        
        // Menuju resources/views/admin/users_index.blade.php
        return view('admin.users_index', compact('users'));
    }

    /**
     * Fungsi Aktifkan/Nonaktifkan Akun secara otomatis.
     */
    public function toggleUserStatus($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        
        // Membalikkan status (true jadi false, false jadi true)
        $user->is_active = !$user->is_active; 
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    /**
     * Menampilkan halaman respon kendala.
     */
    public function showRespon($id): View
    {
        // Mengambil data tiket berdasarkan ID
        $ticket = Ticket::findOrFail($id); 

        // Mengarah ke folder admin -> support -> respon.blade.php
        return view('admin.support.respon', compact('ticket'));
    }

    /**
     * Menyimpan jawaban admin untuk kendala user dan update status.
     */
    public function storeRespon(Request $request, $id): RedirectResponse
    {
        // Validasi minimal 10 karakter sesuai permintaan Anda
        $request->validate([
            'jawaban' => 'required|string|min:10',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Update jawaban admin dan ubah status menjadi RESOLVED
        $ticket->update([
            'jawaban_admin' => $request->jawaban,
            'status' => 'RESOLVED' 
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Respon berhasil dikirim ke pengguna!');
    }


    /**
     * --- FITUR BAWAAN: PROFILE USER ---
     */

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}