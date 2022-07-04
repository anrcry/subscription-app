<?php

namespace App\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class NotifySubscriber extends Mailable
{
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        
        public string $toEmailAddress, 
        public string|null $nameofRecipient = null,
        public string|null $emailSubject = null,
        public string $post_title,
        public string $post_contents,
        public array|null $emailHeaders = null,
    )
    {
        list(
            $this->toEmailAddress, 
            $this->nameofRecipient,
            $this->emailSubject,
            $this->post_title,
            $this->post_contents, 
            $this->emailHeaders
        ) = array(
            $toEmailAddress, 
            $nameofRecipient,
            $emailSubject,
            $post_title, 
            $post_contents,
            $emailHeaders
        );

        $this->emailHeaders = array_merge(config('mail.custom_headers') ?? [], $this->emailHeaders ?? []);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(NULL !== $this->emailHeaders && count($this->emailHeaders) > 0){
            $emailHeaders = $this->emailHeaders;

            \Illuminate\Support\Facades\Log::info($emailHeaders);
    
            $this->withSwiftMessage(function ($message) use ($emailHeaders) {
                $headers = $message->getHeaders();
                foreach($emailHeaders as $k=>$v)
                    $headers->addTextHeader($k, $v);
            });
        }

        return $this->to($this->toEmailAddress, $this->nameofRecipient)
            ->subject($this->emailSubject ?? "")
            ->view('emails.newpost', [
                'name'=>$this->nameofRecipient ?? $this->toEmailAddress,
                'post_title'=> $this->post_title,
                'post_contents' => $this->post_contents
            ]
        );
    }
}
