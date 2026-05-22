<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Servis;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama dulu biar tidak duplikat
        User::truncate();

        // Admin user
        User::create([
            'name'     => 'Admin Chrisma Motor',
            'email'    => 'admin@chrismamotor.com',
            'password' => Hash::make('password123'),
        ]);

        // Kategori
        $oli   = Kategori::create(['nama' => 'Oli & Pelumas']);
        $spare = Kategori::create(['nama' => 'Spare Part']);
        $aki   = Kategori::create(['nama' => 'Aki & Kelistrikan']);
        $ban   = Kategori::create(['nama' => 'Ban & Velg']);

        // Barang
        Barang::insert([
            ['kategori_id'=>$oli->id,   'kode'=>'OLI-001', 'nama'=>'Oli Mesin Federal 0.8L',  'stok'=>50, 'harga_beli'=>18000, 'harga_jual'=>25000, 'satuan'=>'botol', 'keterangan'=>null, 'created_at'=>now(), 'updated_at'=>now()],
            ['kategori_id'=>$oli->id,   'kode'=>'OLI-002', 'nama'=>'Oli Gardan Yamalube',      'stok'=>30, 'harga_beli'=>15000, 'harga_jual'=>22000, 'satuan'=>'botol', 'keterangan'=>null, 'created_at'=>now(), 'updated_at'=>now()],
            ['kategori_id'=>$spare->id, 'kode'=>'SPR-001', 'nama'=>'Busi NGK CR6HSA',          'stok'=>40, 'harga_beli'=>18000, 'harga_jual'=>28000, 'satuan'=>'pcs',   'keterangan'=>null, 'created_at'=>now(), 'updated_at'=>now()],
            ['kategori_id'=>$spare->id, 'kode'=>'SPR-002', 'nama'=>'Filter Udara Honda Beat',  'stok'=>20, 'harga_beli'=>25000, 'harga_jual'=>38000, 'satuan'=>'pcs',   'keterangan'=>null, 'created_at'=>now(), 'updated_at'=>now()],
            ['kategori_id'=>$aki->id,   'kode'=>'AKI-001', 'nama'=>'Aki GS Astra 5Ah',         'stok'=>15, 'harga_beli'=>130000,'harga_jual'=>185000,'satuan'=>'pcs',   'keterangan'=>null, 'created_at'=>now(), 'updated_at'=>now()],
            ['kategori_id'=>$ban->id,   'kode'=>'BAN-001', 'nama'=>'Ban Luar IRC 70/90-14',    'stok'=>12, 'harga_beli'=>110000,'harga_jual'=>160000,'satuan'=>'pcs',   'keterangan'=>null, 'created_at'=>now(), 'updated_at'=>now()],
        ]);

        // Servis
        Servis::insert([
            ['nama'=>'Ganti Oli Mesin',   'harga'=>15000, 'aktif'=>true, 'keterangan'=>null,               'created_at'=>now(), 'updated_at'=>now()],
            ['nama'=>'Servis Karburator', 'harga'=>35000, 'aktif'=>true, 'keterangan'=>null,               'created_at'=>now(), 'updated_at'=>now()],
            ['nama'=>'Tune Up Ringan',    'harga'=>50000, 'aktif'=>true, 'keterangan'=>null,               'created_at'=>now(), 'updated_at'=>now()],
            ['nama'=>'Ganti Ban Luar',    'harga'=>10000, 'aktif'=>true, 'keterangan'=>'Ongkos pasang saja','created_at'=>now(), 'updated_at'=>now()],
            ['nama'=>'Servis Rem',        'harga'=>25000, 'aktif'=>true, 'keterangan'=>null,               'created_at'=>now(), 'updated_at'=>now()],
            ['nama'=>'Servis Kelistrikan','harga'=>40000, 'aktif'=>true, 'keterangan'=>null,               'created_at'=>now(), 'updated_at'=>now()],
        ]);
    }
}