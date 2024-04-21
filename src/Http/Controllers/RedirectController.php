<?php

namespace Alhelwany\LaravelEcash\Http\Controllers;

use Alhelwany\LaravelEcash\Enums\PaymentStatus;
use Alhelwany\LaravelEcash\Events\PaymentStatusUpdated;
use Alhelwany\LaravelEcash\Http\Requests\RedirectRequest;
use Alhelwany\LaravelEcash\Models\EcashPayment;

class RedirectController
{
    /**
     * @param RedirectRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(RedirectRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
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
