<?php

namespace App\Listeners;

use App\Events\InputPaymentCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\Payment\Notification;
use Illuminate\Support\Facades\Mail;
use Exception;

class SendEmailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param InputPaymentCompleted $event
     * @return void
     * @TODO 暫定対応でnumOfPeopleの引数をハードコーディングしているが、
     * calcPayPerPerson()の引数デフォ値と一緒に定数にして呼び出す様修正する。
     */
    public function handle(InputPaymentCompleted $event)
    {
        try {
            Mail::to($event->email)->send(new Notification(
                    $event->name,
                    $event->year,
                    $event->month,
                    $event->payments,
                    $event->paymentSum,
                    $event->totalAmount,
                    $event->calcResult,
                    '2'
                )
            );
        } catch (Exception $e) {
            logger("Send mail failed. mail to {$event->email}");
            report($e);
        }
    }
}
