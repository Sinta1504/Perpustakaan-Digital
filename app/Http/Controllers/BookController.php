<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User; // Tambahkan ini
use App\Models\Loan; // Tambahkan ini
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Menampilkan Halaman Inventori (Dashboard Admin Lengkap)
     */
    public function inventory()
    {
        // 1. Buku paling sering dipinjam (Top 5)
        $topBooks = Book::withCount('loans')->orderBy('loans_count', 'desc')->take(5)->get();

        // 2. Akun yang tidak pinjam > 5 bulan (Status Nonaktif)
        $inactiveUsers = User::whereDoesntHave('loans', function($query) {
            $query->where('created_at', '>=', now()->subMonths(5));
        })->where('role', 'user')->get();

        // 3. Status Buku (Rusak/Lengkap)
        $brokenBooksCount = Book::where('status', 'rusak')->count();
        
        // 4. Buku yang sedang dipinjam & belum kembali
        $activeLoans = Loan::where('status', 'dipinjam')->with(['user', 'book'])->get();

        // Ambil semua buku untuk daftar tabel inventori
        $books = Book::latest()->get(); 

        return view('admin.inventory', compact('topBooks', 'inactiveUsers', 'brokenBooksCount', 'activeLoans', 'books'));
    }

    /**
     * Menampilkan Halaman Beranda (Welcome)
     */
    public function home()
    {
        $books = Book::latest()->take(4)->get();
        return view('home', compact('books'));
    }

    /**
     * Menampilkan Halaman Katalog Lengkap
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
     * Menampilkan Form Tambah Buku
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Menyimpan Buku Baru dengan Gambar Otomatis
     */
    public function store(Request $request) 
    {
        $judul = $request->judul;
        
        // --- LOGIKA AMBIL GAMBAR OTOMATIS ---
        $query = urlencode($judul);
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . $query . "&maxResults=1";
        
        // Gunakan @ untuk menghindari warning jika koneksi gagal
        $response = @file_get_contents($apiUrl);
        $data = json_decode($response, true);
        
        $gambar_url = "https://via.placeholder.com/400x600?text=No+Cover"; 
        if (isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
            $gambar_url = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
        }

        Book::create([
            'judul' => $judul,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'stok' => $request->stok ?? 0,
            'cover_url' => $gambar_url,
            'status' => 'baik', // Status default saat input buku
        ]);

        return redirect()->route('admin.inventory')->with('success', 'Buku berhasil ditambahkan!');
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
            'stok' => 'required|integer|min:0',
        ]);

        $book->update($request->all());

        return redirect()->route('admin.inventory')->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Menghapus Buku
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->back()->with('success', 'Buku telah dihapus dari koleksi.');
    }
}