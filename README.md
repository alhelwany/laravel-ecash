# Laravel Payment Gateway for Ecash (Syria)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/Organon/laravel-ecash.svg?style=flat-square)](https://packagist.org/packages/Organon/laravel-ecash)

Simplify the integration of Ecash payments into your Laravel applications. This package offers a streamlined setup and intuitive API to process payments quickly and securely. 


## Installation

You can install the package via composer:

```bash
composer require Organon/laravel-ecash
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-ecash-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-ecash-config"
```

This is the contents of the published config file:

```php
return [
    'gatewayUrl' => env('ECASH_GATEWAY_URL', 'https://checkout.ecash-pay.co'),
    'terminalKey' => env('ECASH_TERMINAL_KEY', null),
    'merchantId' => env('ECASH_MERCHANT_ID', null),
    'merchantSecret' => env('ECASH_MERCHANT_SECRET', null),
];
```
## Getting Started
1. Setup your environment variables
2. To start the payment process, use the checkout method to create a payment model & generate the payment URL
3. Once the payment is complete, and the gateway redirects the user to the redirect URL, the payment status changes from PENDING to PROCESSING
4. Once the gateway calls the callback URL, the payment status Changes from PROCESSING to either FAILED or PAID
5. On each payment status change, a PaymentStatusUpdated event is fired, you may configure a listener to update the status of your order


## Enums
Enums are in the namespace "Organon\LaravelEcash\Enums"
```php
enum Lang: string
{
    case AR = 'AR';
    case EN = 'EN';
}
```
```php
enum Currency: string
{
    case SYP = 'SYP'; // The only available currency by the gateway so far 
}
```
```php
enum CheckoutType: string
{
    case QR = 'QR';
    case CARD = 'Card';
}
```
```php
enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case PAID = 'paid';
    case FAILED = 'failed';
}
```
## Events
### PaymentStatusUpdated
```php
namespace Organon\LaravelEcash\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Organon\LaravelEcash\Models\EcashPayment;

class PaymentStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(private EcashPayment $paymentModel)
    {
    }

    public function getPaymentModel(): EcashPayment
    {
        return $this->paymentModel;
    }
}
```
## Example Usage
### Checkout


```php
use App\Http\Controllers\Controller;

use Organon\LaravelEcash\Facades\LaravelEcashClient;
use Organon\LaravelEcash\DataObjects\PaymentDataObject;
use Organon\LaravelEcash\Models\EcashPayment;
use Organon\LaravelEcash\Enums\CheckoutType;
use Organon\LaravelEcash\Enums\Lang;
use Organon\LaravelEcash\Enums\Currency;

class ExampleController extends Controller
{
    public function checkout($request)
    {
        $paymentDataObject = new PaymentDataObject(CheckoutType::CARD, 100.10);

        $paymentDataObject->setRedirectUrl(route('payment-successful')); //optional
        $paymentDataObject->setLang(Lang::EN); //optional
        $paymentDataObject->setCurrency(Currency::SYP); //optional

        $result = LaravelEcashClient::checkout($paymentDataObject);

        /** @var EcashPayment */
        $paymentModel = $result['model'];
        $paymentUrl = $result['url'];

        //You may attach the payment model to your order

        return redirect($paymentUrl);
    }
}
```

## Testing

```bash
composer test
```

## Credits

- [Mhd Ghaith Alhelwany](https://github.com/MhdGhaithAlhelwany)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
