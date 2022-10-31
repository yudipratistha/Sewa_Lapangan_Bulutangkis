<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeStatusCourt extends Model
{
    use HasFactory;

    protected $table = 'tb_tipe_status_court';

    protected $guarded = ['id'];
}
