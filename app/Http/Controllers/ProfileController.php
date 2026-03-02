<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
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
        
        // Pastikan file view ini ada di resources/views/admin/users_index.blade.php
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