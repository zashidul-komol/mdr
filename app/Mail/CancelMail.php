<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelMail extends Mailable
{
    public $usersInfo;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usersInfo)
    {
        //dd($data);
        $this->usersInfo=$usersInfo;
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
        $subject ='Cancel Application';
        return $this->markdown('emails.cancelMail')
        ->subject($subject)
        ->with([
            'usersInfo'=> $this->usersInfo
        ])
        ;
    }
}
