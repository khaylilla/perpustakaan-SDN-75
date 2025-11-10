<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #b8b8b8ff;
            color: #000000ff;
            text-align: center;
        }

        .badge-dipinjam {
            background-color: #ffc107;
            color: #000;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .badge-kembali {
            background-color: #28a745;
            color: #fff;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Daftar Peminjaman Buku</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>NPM</th>
                <th>Judul Buku</th>
                <th>Nomor Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->npm }}</td>
                <td>{{ $p->judul_buku }}</td>
                <td>{{ $p->nomor_buku }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                <td>
                    @if($p->tanggal_kembali)
                        {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') }}
                    @else
                        Belum kembali
                    @endif
                </td>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada data peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
