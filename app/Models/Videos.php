<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'photo', 'video', 'slug', 'category_id'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_video', 'video_id', 'tag_id')->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter($video_query, $filters)
    {

        if (isset($filters['category'])) {
            $video_query->whereHas('category', function ($cat_query) use ($filters) {
                $cat_query->where('name', $filters['category']);
            });
        }

        if (isset($filters['tags'])) {
            $tags = explode(',', $filters['tags']);
            $video_query->whereHas('tags', function ($tag_query) use ($tags) {
                $tag_query->whereIn('name', $tags);
            });
        }
    }
}
