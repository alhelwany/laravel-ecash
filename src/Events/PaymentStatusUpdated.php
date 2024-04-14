<?php

namespace Organon\LaravelEcash\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param \Organon\LaravelEcash\Models\EcashPayment $paymentModel
     */
    public function __construct(private \Organon\LaravelEcash\Models\EcashPayment $paymentModel)
    {
    }

    /**
     * @return \Organon\LaravelEcash\Models\EcashPayment
     */
    public function getPaymentModel(): \Organon\LaravelEcash\Models\EcashPayment
    {
        return $this->paymentModel;
    }
}
