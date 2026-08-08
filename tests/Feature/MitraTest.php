<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Gudang;
use App\Models\JenisKentang;
use App\Models\MetodePembayaran;
use App\Models\PenjualanBuah;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MitraTest extends TestCase
{
    use DatabaseTransactions;

    protected $mitra;
    protected $koperasi;
    protected $jenisKentang;
    protected $metode;

    protected function setUp(): void
    {
        parent::setUp();

        // Retrieve seeded Mitra
        $this->mitra = User::where('role', 'mitra')->first();
        if (!$this->mitra) {
            $this->mitra = User::create([
                'name' => 'PT. Horti Agro Makro (CHAMP)',
                'email' => 'mitrachamp@gmail.com',
                'role' => 'mitra',
                'password' => bcrypt('mitrachampst')
            ]);
        }

        // Retrieve seeded Koperasi
        $this->koperasi = User::where('role', 'koperasi')->first();
        if (!$this->koperasi) {
            $this->koperasi = User::create([
                'name' => 'Koperasi',
                'email' => 'koperasi@gmail.com',
                'role' => 'koperasi',
                'password' => bcrypt('koperasist')
            ]);
        }

        // Ensure Mitra Gudang exists
        $gudang = Gudang::where('user_id', $this->mitra->id)->first();
        if (!$gudang) {
            Gudang::create([
                'nama_gudang' => 'PT. Horti Agro Makro (CHAMP)',
                'alamat' => 'Alamat Mitra',
                'kapasitas_max' => 50000,
                'status' => 'Aktif',
                'jenis_gudang' => 'mitra',
                'user_id' => $this->mitra->id,
            ]);
        }

        // Create KategoriKentang if not exists
        $kategori = \App\Models\KategoriKentang::firstOrCreate(
            ['nama_kategori' => 'Kentang Konsumsi'],
            ['deskripsi' => 'Kentang untuk konsumsi harian']
        );

        // Create JenisKentang
        $this->jenisKentang = JenisKentang::firstOrCreate(
            ['nama_jenis' => 'Kentang Granola'],
            [
                'kategori_kentang_id' => $kategori->id,
                'satuan' => 'kg',
                'kualitas' => 'Grade A',
                'deskripsi' => 'Granola premium'
            ]
        );

        // Create MetodePembayaran
        $this->metode = MetodePembayaran::firstOrCreate(
            ['bank' => 'BCA'],
            [
                'user_id' => $this->koperasi->id,
                'kategori' => 'Transfer Bank',
                'no_rekening' => '1234567890',
                'atas_nama' => 'Koperasi'
            ]
        );
    }

    public function test_mitra_pages_are_accessible(): void
    {
        $this->actingAs($this->mitra);

        // 1. Pembelian Page
        $this->get(route('mitra.pembelian.index'))
            ->assertOk()
            ->assertSee('Pembelian Kentang dari Koperasi');

        // 2. Penjualan Index Page
        $this->get(route('mitra.penjualan.index'))
            ->assertOk()
            ->assertSee('Penjualan Kentang');

        // 3. Penjualan Create Page
        $this->get(route('mitra.penjualan.create'))
            ->assertOk()
            ->assertSee('Catat Penjualan Kentang');

        // 4. Stok Page
        $this->get(route('mitra.stok.index'))
            ->assertOk()
            ->assertSee('Manajemen Stok Mitra');

        // 5. Pembayaran Page
        $this->get(route('mitra.pembayaran.index'))
            ->assertOk()
            ->assertSee('Manajemen Pembayaran');

        // 6. Pembayaran Create Page
        $this->get(route('mitra.pembayaran.create'))
            ->assertOk()
            ->assertSee('Catat Pembayaran Baru');

        // 7. Laporan Pembelian Page
        $this->get(route('mitra.laporan.pembelian'))
            ->assertOk()
            ->assertSee('Laporan Pembelian Kentang');

        // 8. Laporan Penjualan Page
        $this->get(route('mitra.laporan.penjualan'))
            ->assertOk()
            ->assertSee('Laporan Penjualan Kentang');

        // 9. Layanan Riwayat Pembelian Page
        $this->get(route('mitra.layanan.riwayat-pembelian'))
            ->assertOk()
            ->assertSee('Riwayat Pembelian Kentang');

        // 10. Layanan Riwayat Penjualan Page
        $this->get(route('mitra.layanan.riwayat-penjualan'))
            ->assertOk()
            ->assertSee('Riwayat Penjualan Kentang');
    }

    public function test_mitra_purchase_payment_increases_stock(): void
    {
        $this->actingAs($this->mitra);

        // 1. Create a purchase from Koperasi (status: belum lunas)
        $purchase = PenjualanBuah::create([
            'koperasi_id' => $this->koperasi->id,
            'pembeli_id' => $this->mitra->id,
            'jenis_kentang_id' => $this->jenisKentang->id,
            'jumlah_kg' => 500,
            'total_harga' => 5000000,
            'grade' => 'Grade A',
            'tanggal_transaksi' => now()->toDateString(),
            'status' => 'belum lunas',
        ]);

        // Verify initial stock of Mitra is 0 or whatever is there
        $gudang = Gudang::where('user_id', $this->mitra->id)->first();
        $initialStock = \App\Models\Stok::where('gudang_id', $gudang->id)
            ->where('jenis_kentang_id', $this->jenisKentang->id)
            ->where('grade', 'Grade A')
            ->value('jumlah_stok') ?? 0;

        // 2. Pay for the purchase
        $response = $this->post(route('mitra.pembelian.bayar', $purchase->id));
        $response->assertRedirect();

        // 3. Verify stock has increased by 500 Kg
        $newStock = \App\Models\Stok::where('gudang_id', $gudang->id)
            ->where('jenis_kentang_id', $this->jenisKentang->id)
            ->where('grade', 'Grade A')
            ->value('jumlah_stok');

        $this->assertEquals($initialStock + 500, $newStock);
    }

    public function test_mitra_sale_decreases_stock(): void
    {
        $this->actingAs($this->mitra);

        // First, seed some stock to Gudang Mitra
        $gudang = Gudang::where('user_id', $this->mitra->id)->first();
        $stock = \App\Models\Stok::updateOrCreate(
            [
                'gudang_id' => $gudang->id,
                'jenis_kentang_id' => $this->jenisKentang->id,
                'grade' => 'Grade A'
            ],
            [
                'jumlah_stok' => 1000,
                'stok_dijual' => 1000
            ]
        );

        // Create a buyer
        $buyer = User::create([
            'name' => 'Konsumen Retail',
            'email' => 'retail@example.com',
            'role' => 'petani',
            'password' => bcrypt('password')
        ]);

        // Record a sale of 400 Kg
        $response = $this->post(route('mitra.penjualan.store'), [
            'pembeli_id' => $buyer->id,
            'jenis_kentang_id' => $this->jenisKentang->id,
            'jumlah_kg' => 400,
            'harga_per_kg' => 10000,
            'total_harga' => 4000000,
            'tanggal_transaksi' => now()->toDateString(),
            'grade' => 'Grade A',
            'status' => 'lunas',
        ]);

        $response->assertRedirect(route('mitra.penjualan.index'));

        // Verify stock has decreased to 600 Kg
        $stock->refresh();
        $this->assertEquals(600, $stock->jumlah_stok);
    }
}
