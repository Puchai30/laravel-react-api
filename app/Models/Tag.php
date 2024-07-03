<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function videos()
    {
        return $this->belongsToMany(Tag::class, 'tag_video', 'video_id', 'tag_id');
    }
}
