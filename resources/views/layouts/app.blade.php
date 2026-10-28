<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventaris Barang')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/neo.css') }}?v={{ filemtime(public_path('css/neo.css')) }}" rel="stylesheet">
</head>
<body>
    <div class="app-wrap">
        <svg class="sticker" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <polygon points="98,50 82.8,58.8 91.6,74 74,74 74,91.6 58.8,82.8 50,98 41.2,82.8 26,91.6 26,74 8.4,74 17.2,58.8 2,50 17.2,41.2 8.4,26 26,26 26,8.4 41.2,17.2 50,2 58.8,17.2 74,8.4 74,26 91.6,26 82.8,41.2"
                     fill="#ffd43b" stroke="#111" stroke-width="3" stroke-linejoin="round"/>
            <text x="50" y="57" text-anchor="middle" font-size="22" font-weight="700"
                  font-family="Space Grotesk, sans-serif" fill="#111">EOQ</text>
        </svg>

        <div class="window">
            <div class="window-bar">
                <span class="dot dot-pink"></span>
                <span class="dot dot-yellow"></span>
                <span class="dot dot-blue"></span>
                <div class="url-bar">inventaris-barang / {{ request()->path() === '/' ? 'beranda' : request()->path() }}</div>
            </div>

            <nav class="window-nav">
                <a class="brand" href="{{ route('beranda') }}">inventaris<b>barang</b></a>
                <a class="nav-pill {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}">Beranda</a>
                <a class="nav-pill {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Data Barang</a>
                <a class="nav-pill {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" href="{{ route('transaksi.index') }}">Transaksi</a>
                <a class="nav-pill {{ request()->routeIs('eoq.*') ? 'active' : '' }}" href="{{ route('eoq.index') }}">Analisis EOQ</a>
            </nav>

            <div class="window-body">
                @if (session('sukses'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('sukses') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('gagal'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('gagal') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <div class="footer-kecil">Sistem Informasi Inventaris Barang · Metode Economic Order Quantity (EOQ)</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>