<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaSewaPromo extends Model
{
    use HasFactory;

    protected $table = 'tb_harga_sewa_promo';

    protected $guarded = ['id'];
}
