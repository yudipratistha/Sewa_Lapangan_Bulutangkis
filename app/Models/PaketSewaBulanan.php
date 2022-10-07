<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketSewaBulanan extends Model
{
    use HasFactory;

    protected $table = 'tb_paket_sewa_bulanan';

    protected $guarded = ['id']; 
}
