<?php

namespace App\Jobs;

use App\Mail\NewPostPublishedMail;
use App\Models\Post;
use App\Models\WebsiteSubscriber;
use App\Models\WebsiteSubscriberPostLog;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyWebsiteSubscriberForNewPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Post $post)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        WebsiteSubscriber::where('website_id',$this->post->website_id)->get()->each(function($subscriber){
            try {
                Mail::to($subscriber->email)->send(
                    new NewPostPublishedMail(
                        postTitle: $this->post->title,
                        postDescription: $this->post->description,
                        subscriberName: $subscriber->name,
                        postCreatedDate: $this->post->created_at
                    )
                );

                WebsiteSubscriberPostLog::updateOrCreate([
                    'website_subscriber_id' => $subscriber->id,
                    'post_id'=> $this->post->id
                ],[
                    'website_subscriber_id' => $subscriber->id,
                    'post_id'=> $this->post->id,
                    'is_notified' => true
                ]);
            }catch (Exception $exception){
                Log::error(sprintf('Mail not sent to %s and error message %s',$subscriber->email,$exception->getMessage()));
            }
        });
    }
}
