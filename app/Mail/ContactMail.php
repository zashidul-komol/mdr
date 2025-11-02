<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMail extends Mailable
{
    public $data;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //dd($lastID);
        $this->data=$data;
        //$subject = $this->data['complainant_name'].'Send a Message on '.$this->data['area'];
        //dd($subject);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        //$subject ='Customer Complain :' .$this->data['complainant_name'].' '.' '.'Send a Mail.';
        //dd($data);
        $subject ='Customer Care :'.'Ticket No: '.$this->data['id'];

        return $this->markdown('emails.contactMail')
        ->subject($subject)
        ->with([
            'data'=> $this->data
        ])
        ;
    }
}
