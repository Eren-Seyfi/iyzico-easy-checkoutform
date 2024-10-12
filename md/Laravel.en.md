
# Iyzico Easy CheckoutForm - Laravel 11 Usage Guide

This guide explains how to integrate the Iyzico Easy CheckoutForm library into your Laravel 11 projects.

## Requirements
- Laravel 11
- PHP 7.4 or higher
- Composer

## Installation

### 1. Install the Easy CheckoutForm Package
In the root directory of your Laravel project, run the following command to install the package:
```bash
composer require eren-seyfi/iyzico-easy-checkoutform
```

### 2. Configure the .env File
Add the API keys and other configuration details to your `.env` file:
```plaintext
IYZIPAY_API_KEY=your_api_key
IYZIPAY_SECRET_KEY=your_secret_key
IYZIPAY_STATUS=test
IYZIPAY_BASE_URL_TEST=https://sandbox-api.iyzipay.com
IYZIPAY_BASE_URL_PRODUCTION=https://api.iyzipay.com
IYZIPAY_CALLBACK_URL=https://yourdomain.com/callback
```
> **Note:** Replace `your_api_key` and `your_secret_key` with your actual Iyzico API keys.

## Usage Options

### Method 1: Using the .env File
If you have stored your API information in the `.env` file, you can initialize the `EasyCheckoutForm` class without passing parameters:

```php
<?php
use Iyzico\EasyCheckoutForm\EasyCheckoutForm;

$checkoutForm = new EasyCheckoutForm();
```

### Method 2: Passing Parameters Directly
To send configuration information directly, pass it as an array like this:

```php
<?php
use Iyzico\EasyCheckoutForm\EasyCheckoutForm;

$config = [
    'api_key' => 'your_api_key',
    'secret_key' => 'your_secret_key',
    'status' => 'test',
    'callback_url' => 'https://yourdomain.com/callback',
    'base_url_test' => 'https://sandbox-api.iyzipay.com',
    'base_url_production' => 'https://api.iyzipay.com'
];

$checkoutForm = new EasyCheckoutForm($config);
```

This approach allows you to set `api_key`, `secret_key`, `status`, `callback_url`, `base_url_test`, and `base_url_production` directly without needing a `.env` file.

### Full Usage for Payment Processing in Laravel

Create a controller to handle payment processing in `app/Http/Controllers/PaymentController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iyzico\EasyCheckoutForm\EasyCheckoutForm;

class PaymentController extends Controller
{
    public function initializePayment()
    {
        $config = [
            'api_key' => 'your_api_key',
            'secret_key' => 'your_secret_key',
            'status' => 'test',
            'callback_url' => 'https://yourdomain.com/callback',
            'base_url_test' => 'https://sandbox-api.iyzipay.com',
            'base_url_production' => 'https://api.iyzipay.com'
        ];

        $checkoutForm = new EasyCheckoutForm($config);

        // Configure the payment form
        $checkoutForm->setForm([
            'conversation_id' => '123456789',
            'price' => '150.00',
            'paid_price' => '150.00',
            'basket_id' => 'B12345'
        ]);
        
        // Set other parameters
        $checkoutForm->setBuyer([
            'id' => 'BY789',
            'name' => 'Jane',
            'surname' => 'Doe',
            'phone' => '+905555555555',
            'email' => 'jane.doe@example.com',
            'identity' => '12345678901',
            'address' => 'Istanbul, Turkey',
            'ip' => request()->ip(),
            'city' => 'Istanbul',
            'country' => 'Turkey'
        ]);

        $checkoutForm->setShippingAddress([
            'name' => 'Jane Doe',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'address' => 'Istanbul, Turkey'
        ]);

        $checkoutForm->setBillingAddress([
            'name' => 'Jane Doe',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'address' => 'Istanbul, Turkey'
        ]);

        $checkoutForm->setBasketItem([
            [
                'id' => 'BI101',
                'name' => 'Product 1',
                'category' => 'Electronics',
                'price' => '100.00'
            ],
            [
                'id' => 'BI102',
                'name' => 'Product 2',
                'category' => 'Books',
                'price' => '50.00'
            ]
        ]);

        $response = $checkoutForm->paymentForm();
        
        return view('payment_form', [
            'paymentContent' => $response->getCheckoutFormContent(),
            'status' => $response->getStatus(),
            'iyzicoError' => $response->getErrorMessage()
        ]);
    }
}
```

### Displaying the Payment Form in a View
Create a view file at `resources/views/payment_form.blade.php` and render the payment form content.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Form</title>
</head>
<body>

    @if($status === 'success')
        <h1>Payment Form</h1>
        <div id="iyzipay-checkout-form" class="responsive">
            {!! $paymentContent !!}
        </div>
    @else
        <p>Failed to load payment form: {{ $iyzicoError }}</p>
    @endif

</body>
</html>
```

With this guide, you can quickly integrate the Iyzico Easy CheckoutForm library into your Laravel 11 projects for streamlined payment processing. You can save your settings in `.env` or pass them directly as parameters.
