<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaServis extends Model
{
    protected $fillable = [
        'nota_id',
        'servis_id',
        'nama_servis',
        'harga',
    ];

    public function servis()
    {
        return $this->belongsTo(Servis::class);
    }

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}