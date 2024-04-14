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
        if (is_null($model) || $model->getVerificationCode() != $request->getToken())
            abort(403);

        // only updating status to processing  if it's pending 
        // The callback request that changes the status to paid or failed may happen before the redirect

        if ($model->status == PaymentStatus::PENDING) {
            $model->update(['status' => PaymentStatus::PROCESSING]);
            event(new PaymentStatusUpdated($model));
        }

        return redirect($request->getRedirectUrl());
    }
}
