@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="container py-5">
    <div class="mb-4">
        <h3 class="fw-bold">Peminjaman Saya</h3>
        <p class="text-muted">Daftar peminjaman Anda — terfilter otomatis berdasarkan akun.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Judul Buku</th>
                            <th>Nomor Buku</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Tanggal Pinjam</th>
                            <th class="text-center">Tanggal Kembali</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-bold">{{ $item->judul_buku }}</td>
                                <td>{{ $item->nomor_buku }}</td>
                                <td class="text-center">{{ $item->jumlah ?? 1 }}</td>
                                <td class="text-center small text-muted">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                                <td class="text-center small">{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $item->status === 'dipinjam' ? 'warning text-dark' : 'success' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada peminjaman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
