<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            // Admin
            ['name' => 'Admin', 'email' => 'admin@gmail.com', 'role' => 'admin', 'password' => 'adminst'],
            ['name' => 'Admin 1', 'email' => 'admin1@gmail.com', 'role' => 'admin', 'password' => 'adminst'],
            
            // Super Admin
            ['name' => 'Super Admin', 'email' => 'superadmin@gmail.com', 'role' => 'admin', 'password' => 'superadminst'],
            ['name' => 'Super Admin 1', 'email' => 'superadmin1@gmail.com', 'role' => 'admin', 'password' => 'superadminst'],
            
            // Koperasi
            ['name' => 'Faisal Koperasi', 'email' => 'faisal@gmail.com', 'role' => 'koperasi', 'password' => 'faisalst'],
            ['name' => 'Cici Muda', 'email' => 'xixi@gmail.com', 'role' => 'koperasi', 'password' => 'xixist'],
            ['name' => 'Koperasi', 'email' => 'koperasi@gmail.com', 'role' => 'koperasi', 'password' => 'koperasist'],
            
            // Petani
            ['name' => 'Habibi Petani', 'email' => 'habibi@gmail.com', 'role' => 'petani', 'password' => 'habibist'],
            ['name' => 'Rofiq Cikajang', 'email' => 'rofiq@gmail.com', 'role' => 'petani', 'password' => 'rofiqst'],
            ['name' => 'Petani', 'email' => 'petani@gmail.com', 'role' => 'petani', 'password' => 'petanist'],

            // Mitra (PT Camp)
            ['name' => 'PT. Horti Agro Makro (CHAMP)', 'email' => 'mitrachamp@gmail.com', 'role' => 'mitra', 'password' => 'mitrachampst'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make($user['password']),
                ]
            );
        }

        // Seed default Gudang Mitra
        $mitraUser = User::where('email', 'mitrachamp@gmail.com')->first();
        if ($mitraUser) {
            \App\Models\Gudang::updateOrCreate(
                ['nama_gudang' => 'PT. Horti Agro Makro (CHAMP)'],
                [
                    'alamat' => 'Jl. Cimanuk No.99, Muara Sanding, Kec. Garut Kota, Kabupaten Garut, Jawa Barat 44119',
                    'provinsi' => 'JAWA BARAT',
                    'kota' => 'KABUPATEN GARUT',
                    'kecamatan' => 'GARUT KOTA',
                    'kelurahan' => 'MUARA SANDING',
                    'latitude' => -7.22647805,
                    'longitude' => 107.90107369,
                    'kapasitas_max' => 50000,
                    'status' => 'Aktif',
                    'jenis_gudang' => 'mitra',
                    'user_id' => $mitraUser->id,
                ]
            );
        }

        // Seed Gudang Koperasi
        \App\Models\Gudang::updateOrCreate(
            ['nama_gudang' => 'Gudang Pusat Koperasi'],
            [
                'alamat' => 'Alamat Koperasi Pusat',
                'provinsi' => 'JAWA BARAT',
                'kota' => 'KABUPATEN GARUT',
                'kecamatan' => 'GARUT KOTA',
                'kelurahan' => 'MUARA SANDING',
                'latitude' => -7.20000000,
                'longitude' => 107.80000000,
                'kapasitas_max' => 100000,
                'status' => 'aktif',
                'jenis_gudang' => 'koperasi',
            ]
        );

        // Seed Gudang Petani
        $petaniUser = User::where('email', 'habibi@gmail.com')->first();
        if ($petaniUser) {
            \App\Models\Gudang::updateOrCreate(
                ['nama_gudang' => 'gudang kurus'],
                [
                    'alamat' => 'Jl. Jendral Sudirman No.16, RT.05/RW.07, Haurpanggung, Kec. Tarogong Kidul, Kabupaten Garut, Jawa Barat 44151',
                    'provinsi' => 'JAWA BARAT',
                    'kota' => 'KABUPATEN BOGOR',
                    'kecamatan' => 'NANGGUNG',
                    'kelurahan' => 'MALASARI',
                    'latitude' => -6.71571268,
                    'longitude' => 106.51489735,
                    'kapasitas_max' => 50000,
                    'status' => 'Aktif',
                    'jenis_gudang' => 'petani',
                    'user_id' => $petaniUser->id,
                ]
            );
        }
    }

}
