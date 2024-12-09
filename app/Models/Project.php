<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'live_link',
        'repo_link',
        'status',
        'start_date',
        'end_date',
    ];
}
