
# Iyzico Easy CheckoutForm - Laravel Kullanım Rehberi

Bu rehber, Iyzico Easy CheckoutForm kütüphanesini Laravel projelerinizde nasıl kullanabileceğinizi açıklamaktadır.

## Gereksinimler
- Laravel (v11 veya üstü)
- PHP 7.4 veya üstü
- Composer

## Kurulum

### 1. Easy CheckoutForm Paketini Yükleyin
Laravel projenizin kök dizininde aşağıdaki komutu çalıştırarak paketi yükleyin:
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
Eğer API bilgilerini `.env` dosyasında tanımladıysanız, `EasyCheckoutForm` sınıfını parametre vermeden başlatabilirsiniz:

```php
<?php
require 'vendor/autoload.php';

use Iyzico\EasyCheckoutForm\EasyCheckoutForm;

$checkoutForm = new EasyCheckoutForm();
```

#### Yöntem 2: Parametre Olarak Bilgileri Göndererek Kullanım
Eğer bilgileri `.env` dışında doğrudan parametre olarak göndermek isterseniz, yapılandırma bilgilerini aşağıdaki gibi dizi olarak verebilirsiniz:

```php
<?php
require 'vendor/autoload.php';

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

Bu iki yapılandırma yöntemini kullanarak `EasyCheckoutForm` sınıfını başlattıktan sonra, ödeme işlemini gerçekleştirmek için aşağıdaki gibi devam edebilirsiniz:

### 4. Ödeme İşlemi İçin Tam Kullanım
```php
try {
    // Form parametrelerini ayarlayın
    $checkoutForm->setForm([
        'conversation_id' => '123456789',
        'price' => '150.00',
        'paid_price' => '150.00',
        'basket_id' => 'B12345'
    ]);

    // Kullanıcı bilgilerini ayarlayın
    $checkoutForm->setBuyer([
        'id' => 'BY789',
        'name' => 'Jane',
        'surname' => 'Doe',
        'phone' => '+905555555555',
        'email' => 'jane.doe@example.com',
        'identity' => '12345678901',
        'address' => 'İstanbul, Türkiye',
        'ip' => request()->ip(),
        'city' => 'Istanbul',
        'country' => 'Turkey'
    ]);

    // Teslimat adresini ayarlayın
    $checkoutForm->setShippingAddress([
        'name' => 'Jane Doe',
        'city' => 'Istanbul',
        'country' => 'Turkey',
        'address' => 'İstanbul, Türkiye'
    ]);

    // Fatura adresini ayarlayın
    $checkoutForm->setBillingAddress([
        'name' => 'Jane Doe',
        'city' => 'Istanbul',
        'country' => 'Turkey',
        'address' => 'İstanbul, Türkiye'
    ]);

    // Sepet öğelerini ekleyin
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
    echo "<h2>Ödeme Formu Başlatıldı:</h2>";
    echo "<div id='iyzipay-checkout-form' class='responsive'></div>";
    echo "<pre>";
    print_r($response);
    echo "</pre>";
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
```

### Route (Yönlendirme)
`routes/web.php` dosyasına ilgili yönlendirmeyi ekleyin:

```php
use App\Http\Controllers\PaymentController;

Route::get('/payment', [PaymentController::class, 'initializePayment']);
```

## Kullanım
Artık, Laravel projenizde `/payment` adresine giderek `payment_form` görünüm dosyasını çalıştırabilir ve Iyzico ödeme formunu doğrudan görüntüleyebilirsiniz.
