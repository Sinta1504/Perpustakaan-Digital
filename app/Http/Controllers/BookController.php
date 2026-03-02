<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookController extends Controller
{
    /**
     * Menampilkan Halaman Inventori (Dashboard Admin Lengkap)
     */
    public function inventory()
    {
        // 1. Buku paling sering dipinjam (Top 5) berdasarkan relasi loans
        $topBooks = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        // 2. Akun yang tidak pinjam > 5 bulan (Status Pasif)
        $fiveMonthsAgo = Carbon::now()->subMonths(5);
        $inactiveUsers = User::where('role', 'user')
            ->whereDoesntHave('loans', function($query) use ($fiveMonthsAgo) {
                $query->where('created_at', '>=', $fiveMonthsAgo);
            })->get();

        // 3. Status Buku Rusak
        $brokenBooksCount = Book::where('status', 'rusak')->count();
        
        // 4. Peminjaman Aktif
        $activeLoans = Loan::where('status', 'dipinjam')
            ->with(['user', 'book'])
            ->latest()
            ->get();

        // 5. Ambil SEMUA buku untuk inventori
        $books = Book::latest()->get(); 

        return view('admin.inventory', compact(
            'topBooks', 
            'inactiveUsers', 
            'brokenBooksCount', 
            'activeLoans', 
            'books'
        ));
    }

    /**
     * Menampilkan 4 buku terbaru di Landing Page
     */
    public function home()
    {
        $books = Book::latest()->take(4)->get();
        return view('home', compact('books'));
    }

    /**
     * Menampilkan Halaman KATALOG (Menampilkan SEMUA buku)
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Fitur Pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        // Mengambil semua hasil tanpa batasan agar semua buku muncul
        $books = $query->latest()->get(); 

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    /**
     * Fitur Simpan Buku (Sudah Disinkronkan menggunakan kolom 'cover')
     */
    public function store(Request $request) 
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        $judul = $request->judul;
        $query = urlencode($judul);
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . $query . "&maxResults=1";
        
        $response = @file_get_contents($apiUrl);
        $data = json_decode($response, true);
        
        $gambar_url = "https://via.placeholder.com/400x600?text=No+Cover"; 
        if (isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
            $gambar_url = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
        }

        // Perbaikan: Menggunakan kolom 'cover' agar sinkron dengan database/seeder
        Book::create([
            'judul' => $judul,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'cover' => $gambar_url, 
            'status' => 'baik', 
        ]);

        return redirect()->route('admin.inventory')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

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

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->back()->with('success', 'Buku telah dihapus dari koleksi.');
    }
}