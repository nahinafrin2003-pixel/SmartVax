<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class areas extends Model
{
    use HasFactory;

        protected $fillable = [
            'city',
            'thana',
            'center_name',
        ];
}
