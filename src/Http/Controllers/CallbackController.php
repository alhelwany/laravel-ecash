<?php

namespace Alhelwany\LaravelEcash\Http\Controllers;

use Alhelwany\LaravelEcash\Enums\PaymentStatus;
use Alhelwany\LaravelEcash\Events\PaymentStatusUpdated;
use Alhelwany\LaravelEcash\Http\Requests\CallbackRequest;
use Alhelwany\LaravelEcash\Models\EcashPayment;

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
