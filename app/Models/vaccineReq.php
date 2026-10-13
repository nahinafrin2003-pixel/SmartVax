<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class vaccineReq extends Model
{
    use HasFactory;
        protected $table = 'vaccine_reqs';
        protected $fillable = [
            'user_id',
            'Name',
            'date',
            'Vaccine',
            'area',
            
        ];
}

