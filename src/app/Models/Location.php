<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    const ERR_GET_ALL_LOCATIONS = 'E1009';

    protected $fillable = [
        'name',
        'parent_id',
    ];
}
