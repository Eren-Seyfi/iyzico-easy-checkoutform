
# Iyzico Easy CheckoutForm

Iyzico ödeme işlemleri için kolaylaştırılmış bir PHP kütüphanesidir. Bu kütüphane, Iyzico'nun CheckoutForm API'sini kullanarak hızlı ve kolay bir şekilde ödeme işlemlerini gerçekleştirmek için geliştirilmiştir.

---

Iyzico Easy CheckoutForm is a simplified PHP library for handling payment transactions. This library is developed to quickly and easily perform payment transactions using Iyzico's CheckoutForm API.

## Özellikler | Features
- Iyzico CheckoutForm API entegrasyonu | Iyzico CheckoutForm API integration
- Kolay yapılandırma | Easy configuration
- PHPUnit ile test edilebilirlik | Testability with PHPUnit
- API anahtarı ve diğer bilgileri .env dosyasında saklama veya direkt parametre olarak gönderme esnekliği | Flexibility to store API key and other information in .env file or pass them as parameters directly

## Gereksinimler | Requirements
- PHP 7.4 veya üstü | PHP 7.4 or higher
- Composer
- Iyzico API Hesabı ve API Anahtarı | Iyzico API Account and API Key
- `vlucas/phpdotenv` paketi | `vlucas/phpdotenv` package

## Kurulum | Installation
Aşağıdaki adımları izleyerek projeyi yerel makinenize kurabilirsiniz:

Follow these steps to install the project on your local machine:

### 1. Depoyu Klonlayın | Clone the repository
```bash
git clone https://github.com/Eren-Seyfi/iyzico-easy-checkoutform.git
cd iyzico-easy-checkoutform
```

### 2. Gerekli Paketleri Yükleyin | Install the required packages
Composer ile gerekli PHP paketlerini yükleyin:
Install the required PHP packages with Composer:
```bash
composer install
```

### 3. .env Dosyasını Yapılandırın | Configure .env File
Proje kök dizininde `.env` dosyasını oluşturabilir ve aşağıdaki bilgileri girerek API bilgilerinizi saklayabilirsiniz:

You can create a `.env` file in the project root directory and store your API information by entering the following details:
```plaintext
IYZIPAY_API_KEY=your_api_key
IYZIPAY_SECRET_KEY=your_secret_key
IYZIPAY_STATUS=test
IYZIPAY_BASE_URL_TEST=https://sandbox-api.iyzipay.com
IYZIPAY_BASE_URL_PRODUCTION=https://api.iyzipay.com
IYZIPAY_CALLBACK_URL=https://yourdomain.com/callback
```

> Not: Gerçek API anahtarlarınızı kullanmayı unutmayın. | Note: Don’t forget to use your real API keys.

### 4. Testleri Çalıştırın | Run the Tests
PHPUnit kullanarak testleri çalıştırabilirsiniz:
You can run the tests using PHPUnit:
```bash
vendor/bin/phpunit tests
```

Testlerin tamamının geçmesi durumunda, yapılandırmanızın doğru çalıştığını onaylayabilirsiniz.
If all tests pass, you can confirm that your configuration is working correctly.

---

## Kullanım | Usage
Kütüphaneyi kullanmak için iki seçenek sunuyoruz: `.env` dosyasını kullanmak veya bilgileri parametre olarak sınıfa göndermek.

We offer two options for using the library: using the `.env` file or passing the information as parameters to the class.

### Yöntem 1: `.env` Dosyası ile Kullanım | Method 1: Using the `.env` file
```php
<?php
require 'vendor/autoload.php';

use Iyzico\EasyCheckoutForm\EasyCheckoutForm;

$checkoutForm = new EasyCheckoutForm();
```

### Yöntem 2: Parametre Olarak Bilgileri Göndererek Kullanım | Method 2: Passing Information as Parameters
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

You can continue to perform the payment transaction as shown below after initializing the `EasyCheckoutForm` class with these two configuration methods:

### Ödeme İşlemi İçin Tam Kullanım | Full Usage for Payment Transaction
```php
try {
    $checkoutForm->setForm([
        'conversation_id' => '123456789',
        'price' => '150.00',
        'paid_price' => '150.00',
        'basket_id' => 'B12345'
    ]);

    $checkoutForm->setBuyer([
        'id' => 'BY789',
        'name' => 'Jane',
        'surname' => 'Doe',
        'phone' => '+905555555555',
        'email' => 'jane.doe@example.com',
        'identity' => '12345678901',
        'address' => 'İstanbul, Türkiye',
        'ip' => '85.34.78.112',
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
    echo "<h2>Ödeme Formu Başlatıldı:</h2>";
    echo "<div id='iyzipay-checkout-form' class='responsive'></div>";
    echo "<pre>";
    print_r($response);
    echo "</pre>";
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
```

## Guides for Laravel and CodeIgniter

### Laravel
**[Laravel Kullanım Rehberi - Türkçe](md/Laravel.tr.md)**  
**[Laravel Usage Guide - English](md/Laravel.en.md)**

### CodeIgniter
**[CodeIgniter Kullanım Rehberi - Türkçe](md/Codeigniter.tr.md)**  
**[CodeIgniter Usage Guide - English](md/Codeigniter.en)**

## Proje Yapısı | Project Structure
- `src/`: Ana kütüphane dosyalarını içerir | Contains main library files
- `tests/`: PHPUnit test dosyalarını içerir | Contains PHPUnit test files
- `vendor/`: Composer tarafından yüklenen bağımlılıkları içerir | Contains dependencies loaded by Composer

## Katkıda Bulunma | Contributing
Bu projeye katkıda bulunmak için lütfen bir "Pull Request" gönderin ya da proje üzerinde hata bulduysanız bir "Issue" açın.

To contribute to this project, please submit a Pull Request or open an Issue if you find any errors on the project.

## Lisans | License
Bu proje MIT Lisansı ile lisanslanmıştır. Detaylar için `LICENSE` dosyasını inceleyin.

This project is licensed under the MIT License. See the LICENSE file for details.
