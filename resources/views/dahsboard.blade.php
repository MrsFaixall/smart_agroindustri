<!DOCTYPE html>
<html>
<body>
    <h1>Selamat Datang2, {{ Auth::user()->name }}</h1>
    <h3>Menu Utama:</h3>

    <ul>
        <li><a href="/dashboard">Dashboard</a></li>

        @if(Auth::user()->role == 'admin')
            <li><a href="/bbm">Kelola Data BBM</a></li>
            <li><a href="/master-data">Kelola Master Data</a></li>
        @endif

        @if(Auth::user()->role == 'petani')
            <li><a href="/gudang">Gudang Penyimpanan</a></li>
            <li><a href="/panen">Catat Hasil Panen</a></li>
            <li><a href="/pembayaran">Metode Pembayaran</a></li>
        @endif

        @if(Auth::user()->role == 'koperasi')
            <li><a href="/pembelian">Kelola Pembelian</a></li>
            <li><a href="/stok">Monitoring Stok</a></li>
        @endif
    </ul>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>