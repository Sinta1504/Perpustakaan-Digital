<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>E-BOOK DIGITAL - {{ $loan->book->judul }}</title>
    <style>
        /* Pengaturan Dasar */
        @page {
            margin: 2cm;
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            line-height: 1.6; 
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header { 
            text-align: center; 
            border-bottom: 3px solid #2563eb; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        .title { 
            font-size: 26px; 
            font-weight: bold; 
            text-transform: uppercase; 
            color: #0f172a;
            margin-bottom: 5px; 
        }
        .author { 
            font-size: 14px; 
            color: #2563eb; 
            font-style: italic; 
            font-weight: bold;
        }

        /* Konten Utama */
        .content { 
            text-align: justify;
        }
        .section-title { 
            border-left: 5px solid #2563eb; 
            padding-left: 12px; 
            font-weight: bold; 
            font-size: 16px;
            color: #1e293b;
            margin-top: 25px;
            margin-bottom: 15px; 
            text-transform: uppercase;
        }
        .summary-box {
            background: #f8fafc; 
            padding: 20px; 
            border-radius: 10px; 
            border: 1px solid #e2e8f0;
        }
        p {
            margin-bottom: 15px;
            word-wrap: break-word;
        }

        /* Info Box */
        .info-grid {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 8px 0;
            font-size: 13px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #64748b;
            width: 150px;
        }

        /* Watermark Modern */
        .watermark { 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%) rotate(-45deg); 
            opacity: 0.05; 
            font-size: 70px; 
            font-weight: bold; 
            color: #000; 
            z-index: -1000; 
            width: 100%;
            text-align: center;
        }

        /* Footer */
        .footer { 
            position: fixed;
            bottom: -1cm;
            left: 0;
            right: 0;
            height: 2cm;
            font-size: 10px; 
            text-align: center; 
            color: #94a3b8; 
            border-top: 1px solid #e2e8f0; 
            padding-top: 10px; 
        }

        /* Mencegah Konten Terpotong Jelek */
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    {{-- Watermark yang akan muncul di setiap halaman --}}
    <div class="watermark">E-LIB DIGITAL PROPERTY</div>

    <div class="header">
        <div class="title">{{ $loan->book->judul }}</div>
        <div class="author">Penulis: {{ $loan->book->penulis }}</div>
    </div>

    <div class="content">
        <div class="no-break">
            <div class="section-title">Sinopsis & Deskripsi</div>
            <div class="summary-box">
                <p>{{ $loan->book->deskripsi ?? 'Tidak ada deskripsi tersedia untuk buku ini.' }}</p>
            </div>
        </div>

        <div class="no-break">
            <div class="section-title">Detail Penerbitan</div>
            <table class="info-grid">
                <tr>
                    <td class="label">Penerbit</td>
                    <td>: {{ $loan->book->penerbit ?? 'Penerbit Umum' }}</td>
                </tr>
                <tr>
                    <td class="label">Tahun Terbit</td>
                    <td>: {{ $loan->book->tahun_terbit ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Kategori</td>
                    <td>: {{ $loan->book->kategori->nama ?? 'Umum' }}</td>
                </tr>
            </table>
        </div>

        <div class="no-break" style="margin-top: 30px;">
            <div class="section-title">Status Hak Cipta Digital</div>
            <p style="font-size: 12px; color: #475569;">
                E-book ini dipinjam secara legal melalui platform <strong>E-LIB Digital Library</strong>. 
                Penggunaan dokumen ini terbatas hanya untuk kepentingan pendidikan dan bacaan pribadi. 
                Dilarang memperbanyak, mendistribusikan, atau menjual kembali dokumen ini dalam bentuk apapun.
            </p>
        </div>
    </div>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem E-LIB.<br>
        Peminjam: <strong>{{ Auth::user()->name }}</strong> | 
        Tanggal Pinjam: {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/Y') }} | 
        ID Transaksi: #{{ $loan->id }}<br>
        &copy; {{ date('Y') }} E-LIB Digital Library - All Rights Reserved.
    </div>
</body>
</html>