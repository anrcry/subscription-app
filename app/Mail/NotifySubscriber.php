<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifySubscriber extends Mailable
{
    use Queueable, ShouldQueue ,SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, string $reciever ,string $title, string $body)
    {
        list($this->email, $this->title, $this->contents, $this->reciever) = array($email, $title, $body, $reciever);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.newpost');
    }
}
