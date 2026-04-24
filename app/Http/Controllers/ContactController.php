<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ContactController extends Controller
{
    /**
     * Menampilkan halaman Hubungi Kami / Konfigurasi
     */
    public function index()
    {
        // Kita simpan data di file json sederhana agar tidak perlu buat tabel database baru
        $path = storage_path('app/contact_info.json');
        $data = File::exists($path) ? json_decode(File::get($path), true) : [
            'email' => 'SUPPORT@ELIB.ID',
            'whatsapp' => '6282323531345',
            'jam_layanan' => '08.00 - 16.00'
        ];

        return view('contact.index', compact('data'));
    }

    /**
     * Menyimpan perubahan konfigurasi
     */
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'whatsapp' => 'required',
            'jam_layanan' => 'required',
        ]);

        $newData = [
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'jam_layanan' => $request->jam_layanan,
        ];

        // Simpan ke file json di folder storage
        File::put(storage_path('app/contact_info.json'), json_encode($newData));

        return redirect()->back()->with('success', 'Konfigurasi kontak berhasil diperbarui!');
    }
}