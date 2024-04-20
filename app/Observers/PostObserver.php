<?php

namespace App\Observers;

use App\Jobs\NotifyWebsiteSubscriberForNewPostJob;
use Illuminate\Support\Str;

class PostObserver
{

    public function creating($post)
    {
        $post->slug = Str::slug($post->title);
    }

    public function created($post)
    {
        NotifyWebsiteSubscriberForNewPostJob::dispatch($post);
    }


    public function updating($post)
    {

    }

    public function updated($post)
    {

    }


    public function deleting($post)
    {
    }







    public function deleted($post): void
    {
        $post->tags()->detach();
        $post->categories()->detach();
    }


    public function restored($post)
    {

    }


    public function forceDeleted($post)
    {

    }




}
