<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cek Koneksi Database</title>
  <style>
    body { font-family: Poppins, sans-serif; background:#f0f4ff; color:#002080; text-align:center; padding-top:80px; }
    .card { background:white; width:450px; margin:auto; padding:25px; border-radius:15px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
    h2 { color:green; margin-bottom:10px; }
  </style>
</head>
<body>
  <div class="card">
    <h2>✅ Koneksi Berhasil!</h2>
    <p>Database aktif: <strong>{{ $dbName }}</strong></p>
    <a href="{{ route('cek.data') }}" style="color:#002080; font-weight:bold;">Lihat Data Tabel →</a>
  </div>
</body>
</html>