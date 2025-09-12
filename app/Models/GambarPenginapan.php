<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarPenginapan extends Model
{
    protected $guarded = ['id'];

    public function penginapan()
    {
        return $this->belongsTo(Penginapan::class);
    }
}
