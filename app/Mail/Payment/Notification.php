<?php

namespace App\Mail\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $year;
    public string $month;
    public array $payments;
    public string $paymentSum;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $year, $month, $payments, $paymentSum)
    {
        $this->name = $name;
        $this->year = $year;
        $this->month = $month;
        $this->payments = $payments;
        $this->paymentSum = $paymentSum;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('支払い結果通知')->view('emails.payments.notification');
    }
}
