<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InputPaymentCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $email;
    public string $name;
    public string $year;
    public string $month;
    public array $payments;
    public string $paymentSum;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($email, $name, $year, $month, $payments, $paymentSum)
    {
        $this->email = $email;
        $this->name = $name;
        $this->year = $year;
        $this->month = $month;
        $this->payments = $payments;
        $this->paymentSum = $paymentSum;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
