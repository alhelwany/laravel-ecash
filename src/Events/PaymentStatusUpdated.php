<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Evenets;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use MhdGhaithAlhelwany\LaravelEcash\Models\EcashPayment;

class PaymentStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private EcashPayment $paymentModel;

    public function __construct(EcashPayment $paymentModel)
    {
        $this->paymentModel = $paymentModel;
    }

    public function getPaymentModel(): EcashPayment
    {
        return $this->paymentModel;
    }
}
