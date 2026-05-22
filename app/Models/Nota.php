<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $fillable = [
        'nomor_nota',
        'nama_customer',
        'no_hp',
        'plat_nomor',
        'total',
        'catatan',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total'   => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(NotaItem::class);
    }

    public function servisList()
    {
        return $this->hasMany(NotaServis::class);
    }
}