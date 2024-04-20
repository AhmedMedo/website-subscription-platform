<?php

namespace App\Repository;

use App\Models\Post;
use App\Repository\Interfaces\PostRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class PostRepository implements PostRepositoryInterface
{

    public function create(array $data, ?UploadedFile $file): Post
    {
        $post = new Post();
        $post->title = Arr::get($data,'title');
        $post->description = Arr::get($data,'description');
        $post->status = Arr::get($data,'status');
        $post->website_id = Arr::get($data,'website_id');
        $post->save();

        if ($file) {
            $post->addMedia($file)->toMediaCollection('thumbnail');
        }

        if (Arr::get($data,'categories'))
        {
            $post->categories()->attach(Arr::get($data,'categories'));
        }

        if (Arr::get($data,'tags'))
        {
            $post->tags()->attach(Arr::get($data,'tags'));
        }

        return $post;

    }
}
