<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    protected $fillable = [
        'nama',
        'harga',
        'aktif',
        'keterangan',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'harga' => 'decimal:2',
    ];

    public function notaServis()
    {
        return $this->hasMany(NotaServis::class);
    }
}