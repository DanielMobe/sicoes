<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecuperarPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $recuperarPassInfo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recuperarPassInfo)
    {
        $this->recuperarPassInfo = $recuperarPassInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.recuperar_password');
    }
}
