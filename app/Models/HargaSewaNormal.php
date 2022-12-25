<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaSewaNormal extends Model
{
    use HasFactory;

    protected $table = 'tb_harga_sewa_normal';

    protected $guarded = ['id'];
}
