<?php

namespace Organon\LaravelEcash\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Organon\LaravelEcash\Models\EcashPayment;

class PaymentStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(private EcashPayment $paymentModel)
    {
    }

    public function getPaymentModel(): EcashPayment
    {
        return $this->paymentModel;
    }
}
