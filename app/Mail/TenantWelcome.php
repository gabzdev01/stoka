<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $shopName;
    public $ownerName;
    public $shopUrl;
    public $phone;
    public $password;

    public function __construct($shopName, $ownerName, $shopUrl, $phone, $password)
    {
        $this->shopName = $shopName;
        $this->ownerName = $ownerName;
        $this->shopUrl = $shopUrl;
        $this->phone = $phone;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Welcome to Stoka - Your Shop is Ready!')
                    ->view('emails.tenant-welcome');
    }
}
