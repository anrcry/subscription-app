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
        // Then first get all the subscribers...
        $subscribers = $post->website->subscribers()->get();
        
        $details['title'] = $post->title;
        $details['contents'] = $post->contents;

        foreach($subscribers as $subscriber){
            $details['email'] = $subscriber->email;
            $details['reciever'] = $subscriber->name ?? $subscriber->email;
            dispatch(new SendEmailJob($details));
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
