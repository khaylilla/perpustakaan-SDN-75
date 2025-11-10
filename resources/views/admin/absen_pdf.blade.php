<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h2 { text-align: center; margin-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: center; }
    th { background: #eee; }
    .group-title { font-weight: bold; margin-top: 10px; }
  </style>
</head>
<body>
  <h2>Data Absen Perpustakaan Fakultas Teknik </h2>

  @foreach($groupedAbsens as $group => $items)
    <p class="group-title">
      @if($groupBy == 'day')
        {{ \Carbon\Carbon::parse($group)->translatedFormat('d F Y') }}
      @elseif($groupBy == 'month')
        {{ \Carbon\Carbon::parse($group . '-01')->translatedFormat('F Y') }}
      @else
        Tahun {{ $group }}
      @endif
    </p>

    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>NPM</th>
          <th>Program Studi</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $index => $absen)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $absen->nama }}</td>
            <td>{{ $absen->npm }}</td>
            <td>{{ $absen->prodi ?? '-' }}</td>
            <td>{{ $absen->tanggal }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endforeach
</body>
</html>
