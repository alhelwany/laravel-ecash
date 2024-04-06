<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Http\Controllers;

use MhdGhaithAlhelwany\LaravelEcash\Enums\PaymentStatus;
use MhdGhaithAlhelwany\LaravelEcash\Events\PaymentStatusUpdated;
use MhdGhaithAlhelwany\LaravelEcash\Http\Requests\RedirectRequest;
use MhdGhaithAlhelwany\LaravelEcash\Models\EcashPayment;

class RedirectController
{
    public function __invoke(RedirectRequest $request)
    {
        $model = EcashPayment::query()->find($request->getPaymentId());
        if (is_null($model) || $model->getVerificationCode() != $request->getToken() || $model->status != PaymentStatus::PENDING)
            abort(403);
        $model->update(['status' => PaymentStatus::PROCESSING]);
        event(new PaymentStatusUpdated($model));
        return redirect($request->getRedirectUrl());
    }
}
