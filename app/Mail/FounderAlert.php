<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FounderAlert extends Mailable
{
    use Queueable, SerializesModels;
    public $shopName, $ownerName, $phone, $email, $city;
    public function __construct($shopName, $ownerName, $phone, $email = null, $city = null)
    {
        $this->shopName  = $shopName;
        $this->ownerName = $ownerName;
        $this->phone     = $phone;
        $this->email     = $email;
        $this->city      = $city;
    }
    public function build()
    {
        return $this->subject('New registration: ' . $this->shopName)
                    ->view('emails.founder-alert');
    }
}