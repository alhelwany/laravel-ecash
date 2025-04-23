<?php

namespace Alhelwany\LaravelEcash\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case PAID = 'paid';
    case FAILED = 'failed';
    case REVERSED = 'reversed';
}
