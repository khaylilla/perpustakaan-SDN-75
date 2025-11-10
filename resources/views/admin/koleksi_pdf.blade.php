<!DOCTYPE html>
<html>
<head>
    <title>Data Koleksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background: #f0f0f0; }
        .kategori-header { background: #d9edf7; font-weight: bold; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h3>Data Koleksi</h3>

    @php $currentKategori = null; $currentTahun = null; @endphp

    @foreach($books as $book)
        @if($currentKategori != $book->kategori || $currentTahun != $book->tahun_terbit)
            @if($currentKategori != null)
                </table> <!-- Tutup tabel sebelumnya -->
            @endif

            <table>
                <tr>
                    <td colspan="6" class="kategori-header">
                        {{ $book->kategori }} - {{ $book->tahun_terbit }}
                    </td>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Nomor Buku</th>
                    <th>Rak</th>
                </tr>

            @php
                $currentKategori = $book->kategori;
                $currentTahun = $book->tahun_terbit;
                $no = 1;
            @endphp
        @endif

        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $book->judul }}</td>
            <td>{{ $book->penulis }}</td>
            <td>{{ $book->penerbit }}</td>
            <td>{{ $book->nomor_buku }}</td>
            <td>{{ $book->rak }}</td>
        </tr>

        @if($loop->last)
            </table>
        @endif
    @endforeach

</body>
</html>
