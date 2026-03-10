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

        // 5. Suara Peminjam
        try {
            $allReviews = Loan::with(['user', 'book'])
                ->where('status', 'dikembalikan')
                ->whereNotNull('review')
                ->latest()
                ->get();
        } catch (\Exception $e) {
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
     * Menampilkan Halaman KATALOG (Dengan Logika Pencarian)
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Logika Pencarian agar sinkron dengan Search Bar di View
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
     * Fitur Simpan Buku (Perbaikan Path Storage)
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

        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            
            // Simpan ke storage/app/public/covers menggunakan disk 'public'
            $path = $file->storeAs('covers', $namaFile, 'public');

            Book::create([
                'judul' => $request->judul,
                'penulis' => $request->penulis,
                'sinopsis' => $request->sinopsis,
                'kategori' => $request->kategori,
                'stok' => $request->stok,
                'cover' => $path, // Simpan 'covers/nama.jpg'
                'status' => 'baik', 
            ]);
        }

        return redirect()->route('admin.inventory')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Fitur Update Buku (Perbaikan Path Storage)
     */
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
            
            // Hapus file lama jika ada
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            $file = $request->file('cover');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('covers', $namaFile, 'public');
            $book->cover = $path;
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
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }
        $book->delete();
        return redirect()->back()->with('success', 'Buku telah dihapus dari koleksi.');
    }
}