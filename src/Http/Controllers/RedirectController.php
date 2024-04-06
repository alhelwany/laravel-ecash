<?php

namespace Organon\LaravelEcash\Http\Controllers;

use Organon\LaravelEcash\Enums\PaymentStatus;
use Organon\LaravelEcash\Events\PaymentStatusUpdated;
use Organon\LaravelEcash\Http\Requests\RedirectRequest;
use Organon\LaravelEcash\Models\EcashPayment;

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
