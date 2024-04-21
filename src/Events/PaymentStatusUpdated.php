<?php

namespace Alhelwany\LaravelEcash\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param \Alhelwany\LaravelEcash\Models\EcashPayment $paymentModel
     */
    public function __construct(private \Alhelwany\LaravelEcash\Models\EcashPayment $paymentModel)
    {
    }

    /**
     * @return \Alhelwany\LaravelEcash\Models\EcashPayment
     */
    public function getPaymentModel(): \Alhelwany\LaravelEcash\Models\EcashPayment
    {
        return $this->paymentModel;
    }
}
