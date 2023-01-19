<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaPerJamPromo extends Model
{
    use HasFactory;

    protected $table = 'tb_harga_sewa_perjam_promo';

    protected $guarded = ['id'];

    public function Lapangan(){
        return $this->belongsTo('App\Models\Lapangan', 'id');
    }
}
