<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusCourt extends Model
{
    use HasFactory;

    protected $table = 'tb_status_court';

    public function Courts(){
        return $this->belongsTo('App\Models\Courts', 'id');
    }
}
