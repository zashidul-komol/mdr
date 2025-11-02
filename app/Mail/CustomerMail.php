<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerMail extends Mailable
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
        //dd($data);
        $this->data=$data;
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
        $subject ='Requisition No : '.$this->data['maxrequisition_no'];
        return $this->markdown('emails.customerMail')
        ->subject($subject)
        ->with([
            'data'=> $this->data
        ])
        ;
    }
}
