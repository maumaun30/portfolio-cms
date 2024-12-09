<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'menu_id',
        'menu_items'
    ];

    protected $casts = [
        'menu_items' => 'array',
    ];
}