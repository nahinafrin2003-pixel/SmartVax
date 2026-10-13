<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class certificate extends Model
{
    use HasFactory;
        protected $table = 'certificates';
        protected $fillable = [
            'my_name',
            'vaccine_name',
            'status',
            'no_of_doses_taken',
            'next_date',
            'user_id',      
            'request_id',   

        ];
}
