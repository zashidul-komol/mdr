<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaDaBillApproveMail extends Mailable
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
        //$subject = $this->usersInfo[0]->distributors['distributorName'].'Send a Message on '.$this->data['name'];
        //dd($subject);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject ='TA/DA Bill Approved';
        return $this->markdown('emails.TaDaBillapproveMail')
        ->subject($subject)
        ->with([
            'usersInfo'=> $this->usersInfo
        ])
        ;
    }
}
