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
  </style>
</head>
<body>
  <h2>Data Absen Perpustakaan Fakultas Teknik</h2>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NPM</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($absens as $index => $absen)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $absen->nama }}</td>
          <td>{{ $absen->npm }}</td>
          <td>{{ $absen->tanggal }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
