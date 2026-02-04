<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Menampilkan Halaman Beranda (Welcome)
     */
    public function home()
    {
        // Mengambil 4 buku terbaru dari database
        $books = Book::latest()->take(4)->get();

        // Pastikan nama view sesuai (home atau welcome)
        return view('home', compact('books'));
    }

    /**
     * Menampilkan Halaman Katalog Lengkap dengan fitur pencarian
     */
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        $books = $query->latest()->get();
        return view('katalog', compact('books'));
    }

    /**
     * Menampilkan Detail Buku
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Menampilkan Form Tambah Buku (DIUPDATE)
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Menyimpan Buku Baru ke Database (DIUPDATE)
     */
    public function store(Request $request) 
{
    $judul = $request->judul;
    
    // --- LOGIKA AMBIL GAMBAR OTOMATIS ---
    $query = urlencode($judul);
    $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . $query . "&maxResults=1";
    
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);
    
    $gambar_url = "https://via.placeholder.com/150x200?text=No+Cover"; // Default
    if (isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
        $gambar_url = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
    }
    // ------------------------------------

    // Simpan ke database
    Book::create([
        'judul' => $judul,
        'penulis' => $request->penulis,
        'cover_url' => $gambar_url, // Pastikan kolom ini ada di database kamu
    ]);

    return redirect()->back();
}

    /**
     * Menampilkan Form Edit Buku
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Memperbarui Data Buku
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer|min:0', // Menambahkan stok di update juga
        ]);

        $book->update($request->all());

        return redirect()->route('katalog')->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Menghapus Buku
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('katalog')->with('success', 'Buku telah dihapus dari koleksi.');
    }
}