<?php

namespace App\Mail\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationOnePerson extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public string  $name,
        public string  $year,
        public string  $month,
        public array   $payments,
        public string  $paymentSum,
        public ?string $totalAmount
    )
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('支払い結果通知')->view('emails.payments.notification_one_person');
    }
}
