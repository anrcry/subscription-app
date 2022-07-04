<?php

namespace App\Observers;

use App\Models\Post;
use App\Jobs\SendEmailJob;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function created(Post $post)
    {        
        // Get all the subscribers...
        $website = $post->website()->first();
        $subscribers = $post->website->subscribers()->get();

        $subject = "We have a new post \"{$post->title}\" on {$website->name}";

        // Iterate over the subscribers 
        foreach($subscribers as $subscriber){
            // New job
			$job = new SendEmailJob($post->title, $post->contents, $subscriber->email, $subject,$subscriber->name);
            dispatch($job);
        }
    }
 
    /**
     * Handle the Post "updated" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        //
    }
 
    /**
     * Handle the Post "deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        //
    }
 
    /**
     * Handle the Post "restored" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }
 
    /**
     * Handle the Post "forceDeleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        //
    }
}
