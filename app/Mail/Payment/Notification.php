<?php

namespace App\Mail\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $year
     * @param string $month
     * @param array $payments
     * @param string $paymentSum
     * @param string|null $totalAmount
     * @param string|null $calcResult
     * @param string $numOfPeople
     * @return void
     */
    public function __construct(
        public string  $name,
        public string  $year,
        public string  $month,
        public array   $payments,
        public string  $paymentSum,
        public ?string $totalAmount,
        public ?string $calcResult,
        public string  $numOfPeople
    ) {
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
