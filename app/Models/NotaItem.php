<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaItem extends Model
{
    protected $fillable = [
        'nota_id',
        'barang_id',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}