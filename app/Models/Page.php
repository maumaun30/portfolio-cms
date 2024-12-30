<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Z3d0X\FilamentFabricator\Models\Page as BasePage;

class Page extends BasePage
{
    protected $fillable = [
        'is_homepage'
    ];
}
