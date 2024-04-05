<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Enums;

enum CheckoutType: string
{
    case QR = 'QR';
    case CARD = 'Card';
}
