<?php

namespace App\Jobs;

use App\Mail\OrderTripMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailOrderTrip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $order_details;

    /**
     * Create a new job instance.
     */
    public function __construct($order, $order_details)
    {
        $this->order         = $order;
        $this->order_details = $order_details;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->order->email)->send(new OrderTripMail($this->order, $this->order_details));
    }
}
