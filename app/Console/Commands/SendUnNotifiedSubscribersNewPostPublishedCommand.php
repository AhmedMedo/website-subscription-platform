<?php

namespace App\Console\Commands;

use App\Jobs\NotifyWebsiteSubscriberForNewPostJob;
use App\Models\Website;
use App\StatusEnum;
use Illuminate\Console\Command;

class SendUnNotifiedSubscribersNewPostPublishedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-un-notified-subscribers-new-post-published-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send un-notified subscribers new post published';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $websitesWithPostsNotNotified = Website::with(['posts'=> function ($query) {
            $query->whereDoesntHave('postSubscribers')->where('status','=',StatusEnum::ACTIVE->value);

        }])->get();

        $websitesWithPostsNotNotified->each(function($website){
            foreach ($website->posts as $post)
            {
                NotifyWebsiteSubscriberForNewPostJob::dispatch($post);
            }
        });

    }
}
