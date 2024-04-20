<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public function website(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function postSubscribers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebsiteSubscriberPostLog::class, 'post_id', 'id');

    }
}
