<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'excerpt',
        'image_path',
        'author_id',
        'category_id',
        'published_at',
        'status',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    protected $attributes = [
        'category_id' => 1 // Assuming "Uncategorized" has ID 1
    ];

    // Or, use a method to dynamically get the "Uncategorized" ID if it's not 1
    protected static function booted()
    {
        static::creating(function ($post) {
            if (!$post->category_id) {
                $post->category_id = Category::where('slug', 'uncategorized')->value('id');
            }
        });
    }
}
