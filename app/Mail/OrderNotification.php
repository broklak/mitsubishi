<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $type;
    public $order;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type, $order, $user)
    {
        $this->type = $type;
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->type) {
            case 'create':
                $subject = 'Notifikasi SPK Baru '.$this->order->spk_code;
                break;
            case 'update':
                $subject = 'Notifikasi Perubahan SPK '.$this->order->spk_code;
                break;
            default:
                $subject = 'Notifikasi Lain';
                break;
        }
        return $this->subject($subject)
                ->view('mail.notif');
    }
}
