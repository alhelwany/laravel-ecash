<?php

namespace Organon\LaravelEcash\Http\Controllers;

use Organon\LaravelEcash\Enums\PaymentStatus;
use Organon\LaravelEcash\Events\PaymentStatusUpdated;
use Organon\LaravelEcash\Http\Requests\CallbackRequest;
use Organon\LaravelEcash\Models\EcashPayment;

class CallbackController
{
    /**
     * @param CallbackRequest $request
     * @return void
     */
    public function __invoke(CallbackRequest $request): void
    {
        $paymentModel = EcashPayment::query()->find($request->getOrderRef());
        $paymentModel->update([
            'status' => $request->getIsSuccess() ? PaymentStatus::PAID : PaymentStatus::FAILED,
            'message' => $request->getMessage(),
            'transaction_no' => $request->getTransactionNo()
        ]);
        event(new PaymentStatusUpdated($paymentModel));
    }
}
