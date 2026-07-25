# Walkthrough: Pemisahan Transaksi Koperasi & Pembaruan Kategori Kentang

Kita telah memisahkan sistem transaksi koperasi generik (`transaksi_koperasis`) menjadi tiga entitas spesifik, serta memperbarui kategori jenis kentang agar mendukung pengadaan benih & buah konsumsi secara lebih presisi.

## Perubahan yang Dilakukan

### 1. Database & Migrasi (Pemisahan Transaksi)
- Membuat tabel **`pengadaan_benihs`** untuk mencatat pembelian benih dari PT Champ (Mitra) ke Koperasi.
- Membuat tabel **`distribusi_benihs`** untuk mencatat penyaluran benih dari Koperasi ke Petani.
- Membuat tabel **`penjualan_buahs`** untuk mencatat penjualan hasil panen buah kentang Koperasi ke pembeli (Mitra / Konsumen).
- Menghapus tabel generik **`transaksi_koperasis`** yang tidak lagi digunakan.
- Menjalankan perintah migrasi `php artisan migrate` dengan sukses.

### 2. Model Eloquent Terpisah
- **`App\Models\PengadaanBenih`**, **`App\Models\DistribusiBenih`**, dan **`App\Models\PenjualanBuah`** telah dibuat dengan relasi kunci asing yang tepat ke tabel `users` (Koperasi, Mitra, Petani, Pembeli) dan `jenis_kentangs`.

### 3. Kontroler Spesifik & Manajemen Stok
- **`PengadaanBenihController`**, **`DistribusiBenihController`**, dan **`PenjualanBuahController`** mengelola proses penambahan & pemotongan stok pada `Stok` Koperasi dengan tepat sesuai dengan alur fisik masuk/keluar.

### 4. Tampilan View & Navigasi
- Membuat folder & template view spesifik untuk masing-masing transaksi baru.
- Memperbarui rute di `routes/web.php` dan navigasi menu sidebar.

### 5. Pembaruan Kategori Jenis Kentang (Benih Hulu & Kentang Konsumsi)
- Membuat dan menjalankan migrasi database baru yang mengubah opsi kategori pada tabel `jenis_kentangs` dari `['benih', 'buah_konsumsi']` menjadi `['benih_hulu', 'kentang_konsumsi']`.
- Memperbarui formulir input tambah dan edit jenis kentang di panel admin (`admin/jenis_kentang/create` dan `admin/jenis_kentang/edit`) agar admin dapat memilih kategori komoditas.
- Menampilkan kolom Kategori pada tabel daftar Jenis Kentang admin.
- Memperbarui query filter kategori di seluruh kontroler terkait (`PengadaanBenihController`, `DistribusiBenihController`, `PenjualanBuahController`, `StokController`) agar menggunakan nilai kategori baru.
- Memperbarui tampilan koperasi (`gudang&stok` dan `atur-harga-pasar`) agar menampilkan label yang sesuai.
