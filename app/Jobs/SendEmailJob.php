<?php

namespace App\Jobs;

use App\Mail\NotifySubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public string $post_title,
        public string $post_contents,
        public string $email,
        public string $name,
        public string|null $subject = null,
        public array|null $headers = null,
    )
    {
        list($this->post_title, $this->post_contents, $this->email, $this->name, $this->subject,$this->headers) = 
        array($post_title, $post_contents, $email, $name, $subject ,$headers);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notify = new NotifySubscriber($this->email, $this->name, $this->subject,$this->post_title, $this->post_contents, $this->headers);
        
        Mail::send($notify);
    }
}
