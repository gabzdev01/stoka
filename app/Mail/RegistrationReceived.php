<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationReceived extends Mailable
{
    use Queueable, SerializesModels;
    public $shopName, $ownerName;
    public function __construct($shopName, $ownerName)
    {
        $this->shopName  = $shopName;
        $this->ownerName = $ownerName;
    }
    public function build()
    {
        return $this->subject('We received your request — ' . $this->shopName)
                    ->view('emails.registration-received');
    }
}