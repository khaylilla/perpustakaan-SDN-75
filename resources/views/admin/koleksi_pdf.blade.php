<!DOCTYPE html>
<html>
<head>
    <title>Data Koleksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h3>Data Koleksi</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Kategori</th>
                <th>Tahun Terbit</th>
                <th>Nomor Buku</th>
                <th>Rak</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($books as $book)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $book->judul }}</td>
                    <td>{{ $book->penulis }}</td>
                    <td>{{ $book->penerbit }}</td>
                    <td>{{ $book->kategori }}</td>
                    <td>{{ $book->tahun_terbit }}</td>
                    <td>{{ $book->nomor_buku }}</td>
                    <td>{{ $book->rak }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
