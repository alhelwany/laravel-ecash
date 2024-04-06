<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Http\Controllers;

use MhdGhaithAlhelwany\LaravelEcash\Enums\PaymentStatus;
use MhdGhaithAlhelwany\LaravelEcash\Evenets\PaymentStatusUpdated;
use MhdGhaithAlhelwany\LaravelEcash\Http\Requests\CallbackRequest;
use MhdGhaithAlhelwany\LaravelEcash\Models\EcashPayment;

class CallbackController
{
    public function __invoke(CallbackRequest $request)
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
