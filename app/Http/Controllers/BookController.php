<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Menampilkan Halaman Inventori (Dashboard Admin Lengkap)
     */
    public function inventory()
    {
        // 1. Buku paling sering dipinjam (Top 5)
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

        // 5. Suara Peminjam (Dengan pengaman try-catch agar tidak error jika kolom belum ada)
        try {
            $allReviews = Loan::with(['user', 'book'])
                ->where('status', 'dikembalikan')
                ->whereNotNull('review')
                ->latest()
                ->get();
        } catch (\Exception $e) {
            // Jika database error karena kolom review belum ada, buat array kosong agar page tidak crash
            $allReviews = collect();
        }

        // 6. Ambil SEMUA buku untuk inventori
        $books = Book::latest()->get(); 

        return view('admin.inventory', compact(
            'topBooks', 
            'inactiveUsers', 
            'brokenBooksCount', 
            'activeLoans', 
            'books',
            'allReviews'
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

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        $books = $query->latest()->get(); 

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    /**
     * Fitur Simpan Buku (Upload Manual)
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'kategori' => 'required',
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'stok' => 'required|numeric|min:0',
        ]);

        $file = $request->file('cover');
        $namaFile = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/covers', $namaFile);

        Book::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'sinopsis' => $request->sinopsis,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'cover' => 'covers/' . $namaFile, 
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

        if ($request->hasFile('cover')) {
            $request->validate(['cover' => 'image|mimes:jpeg,png,jpg|max:2048']);
            if ($book->cover) {
                Storage::delete('public/' . $book->cover);
            }
            $file = $request->file('cover');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/covers', $namaFile);
            $book->cover = 'covers/' . $namaFile;
        }

        $book->judul = $request->judul;
        $book->penulis = $request->penulis;
        $book->kategori = $request->kategori;
        $book->stok = $request->stok;
        $book->sinopsis = $request->sinopsis;
        $book->save();

        return redirect()->route('admin.inventory')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy(Book $book)
    {
        if ($book->cover) {
            Storage::delete('public/' . $book->cover);
        }
        $book->delete();
        return redirect()->back()->with('success', 'Buku telah dihapus dari koleksi.');
    }
}