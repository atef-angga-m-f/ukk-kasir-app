<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container text-center">
        <h1 class="text-danger">Terjadi Kesalahan</h1>
        <p>{{ session('error') }}</p>
        <a href="{{ route('purchase.index') }}" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
</body>
</html>
