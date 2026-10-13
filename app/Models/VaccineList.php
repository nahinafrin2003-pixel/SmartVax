<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VaccineList extends Model
{
    use HasFactory;
        protected $table = 'vaccine_lists';
        protected $fillable = [
            'vaccine_name',
            'manufacturer',
            'quantity',
            'production_date',
            'expiry_date',
        ];
}
