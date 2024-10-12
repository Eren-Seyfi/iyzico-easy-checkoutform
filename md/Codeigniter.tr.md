
# Iyzico Easy CheckoutForm - CodeIgniter 4 Kullanım Rehberi

Bu rehber, Iyzico Easy CheckoutForm kütüphanesini CodeIgniter 4 projelerinizde nasıl kullanabileceğinizi açıklamaktadır.

## Gereksinimler
- CodeIgniter 4
- PHP 7.4 veya üstü
- Composer

## Kurulum

### 1. Easy CheckoutForm Paketini Yükleyin
Projenizin kök dizininde aşağıdaki komutu çalıştırarak paketi yükleyin:
```bash
composer require eren-seyfi/iyzico-easy-checkoutform
```

### 2. .env Dosyasını Yapılandırın
API anahtarlarını ve diğer yapılandırma bilgilerini `.env` dosyanıza ekleyin (isteğe bağlı):
```plaintext
IYZIPAY_API_KEY=your_api_key
IYZIPAY_SECRET_KEY=your_secret_key
IYZIPAY_STATUS=test
IYZIPAY_BASE_URL_TEST=https://sandbox-api.iyzipay.com
IYZIPAY_BASE_URL_PRODUCTION=https://api.iyzipay.com
IYZIPAY_CALLBACK_URL=https://yourdomain.com/callback
```
> **Not:** `your_api_key` ve `your_secret_key` değerlerini gerçek Iyzico API anahtarlarınız ile değiştirin.

### 3. Kullanım Seçenekleri

#### Yöntem 1: .env Dosyası ile Kullanım
API bilgilerini `.env` dosyasında tanımladıysanız, `EasyCheckoutForm` sınıfını parametre olmadan başlatabilirsiniz:

```php
<?php
use Iyzico\EasyCheckoutForm\EasyCheckoutForm;

$checkoutForm = new EasyCheckoutForm();
```

#### Yöntem 2: Parametre Olarak Bilgileri Göndererek Kullanım
Bilgileri `.env` dışında doğrudan parametre olarak göndermek isterseniz, yapılandırma bilgilerini aşağıdaki gibi dizi olarak verebilirsiniz:

```php
<?php
use Iyzico\EasyCheckoutForm\EasyCheckoutForm;

// Konfigürasyon bilgilerini doğrudan dizi ile iletin
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

Bu yöntemde `EasyCheckoutForm` sınıfı başlatılırken, `.env` yerine `api_key`, `secret_key`, `status`, `callback_url`, `base_url_test`, ve `base_url_production` değerlerini içeren bir dizi gönderilir. Bu, projede API bilgilerini .env dosyasına kaydetmek yerine kod içinde doğrudan tanımlayarak daha esnek bir kullanım sağlar.

### 4. Ödeme İşlemi İçin Tam Kullanım

```php
<?php

namespace App\Controllers;

use Iyzico\EasyCheckoutForm\EasyCheckoutForm;

class Payment extends BaseController
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

        // Ödeme formunu ayarlayın
        $checkoutForm->setForm([
            'conversation_id' => '123456789',
            'price' => '150.00',
            'paid_price' => '150.00',
            'basket_id' => 'B12345'
        ]);
        
        // Diğer parametreleri ayarlayın
        $checkoutForm->setBuyer([
            'id' => 'BY789',
            'name' => 'Jane',
            'surname' => 'Doe',
            'phone' => '+905555555555',
            'email' => 'jane.doe@example.com',
            'identity' => '12345678901',
            'address' => 'İstanbul, Türkiye',
            'ip' => $this->request->getIPAddress(),
            'city' => 'Istanbul',
            'country' => 'Turkey'
        ]);

        $checkoutForm->setShippingAddress([
            'name' => 'Jane Doe',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'address' => 'İstanbul, Türkiye'
        ]);

        $checkoutForm->setBillingAddress([
            'name' => 'Jane Doe',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'address' => 'İstanbul, Türkiye'
        ]);

        $checkoutForm->setBasketItem([
            [
                'id' => 'BI101',
                'name' => 'Ürün 1',
                'category' => 'Elektronik',
                'price' => '100.00'
            ],
            [
                'id' => 'BI102',
                'name' => 'Ürün 2',
                'category' => 'Kitap',
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

### Görünümde Gösterim
Aşağıdaki gibi `app/Views/payment_form.php` dosyasını oluşturun ve ödeme formu içeriğini görüntüleyin.

```html
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme Formu</title>
</head>
<body>

    <?php if($status === 'success'): ?>
        <h1>Ödeme Formu</h1>
        <div id="iyzipay-checkout-form" class="responsive">
            <?= $paymentContent; ?>
        </div>
    <?php else: ?>
        <p>Ödeme formu yüklenemedi: <?= $iyzicoError; ?></p>
    <?php endif; ?>

</body>
</html>
```

Bu rehber ile, CodeIgniter 4 projelerinizde Iyzico Easy CheckoutForm kütüphanesini kullanarak hızlı bir şekilde ödeme işlemleri gerçekleştirebilirsiniz. Parametrelerinizi `.env` dosyasında saklayabilir veya doğrudan koda dahil edebilirsiniz.
