<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'kategori_id',
        'kode',
        'nama',
        'stok',
        'harga_beli',
        'harga_jual',
        'satuan',
        'keterangan',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function notaItems()
    {
        return $this->hasMany(NotaItem::class);
    }
}