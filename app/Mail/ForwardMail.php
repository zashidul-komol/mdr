<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForwardMail extends Mailable
{
    public $users;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($users)
    {
        //dd($data);
        $this->users=$users;
        //$subject = $this->data['name'].'Send a Message on '.$this->data['name'];
        //dd($subject);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject ='Application Forward';
        return $this->markdown('emails.forwardMail')
        ->subject($subject)
        ->with([
            'users'=> $this->users
        ])
        ;
    }
}
