<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContactForm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $topic
     * @param $name
     * @param $email
     * @param $message
     */
    public function __construct(private $topic, private $name, private $email, private $message)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Contacts Form - '. config('app.name'))->markdown('emails.contacts', [
            'topic' => $this->topic,
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message
        ]);
    }
}
