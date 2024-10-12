<?php

use PHPUnit\Framework\TestCase;
use Iyzico\EasyCheckoutForm\EasyCheckoutForm;

class EasyCheckoutFormTest extends TestCase
{
    protected $checkoutForm;

    protected function setUp(): void
    {
        // Test yapılandırma ayarlarını burada tanımlayın
        $config = [
            'api_key' => 'test_api_key',
            'secret_key' => 'test_secret_key',
            'status' => 'test',
            'callback_url' => 'https://yourdomain.com/callback',
            'base_url_test' => 'https://sandbox-api.iyzipay.com',
            'base_url_production' => 'https://api.iyzipay.com'
        ];

        // EasyCheckoutForm örneğini yapılandırma dizisiyle oluştur
        $this->checkoutForm = new EasyCheckoutForm($config);
    }

    public function testSetForm()
    {
        $params = [
            'conversation_id' => '123456789',
            'price' => '100.00',
            'paid_price' => '100.00',
            'basket_id' => 'B67832'
        ];

        $result = $this->checkoutForm->setForm($params);
        $this->assertInstanceOf(EasyCheckoutForm::class, $result);
    }

    public function testSetBuyer()
    {
        $params = [
            'id' => 'BY789',
            'name' => 'John',
            'surname' => 'Doe',
            'phone' => '+905555555555',
            'email' => 'john.doe@example.com',
            'identity' => '12345678901',
            'address' => 'Sample Address',
            'ip' => '85.34.78.112',
            'city' => 'Istanbul',
            'country' => 'Turkey'
        ];

        $result = $this->checkoutForm->setBuyer($params);
        $this->assertInstanceOf(EasyCheckoutForm::class, $result);
    }

    public function testSetShippingAddress()
    {
        $params = [
            'name' => 'John Doe',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'address' => 'Sample Shipping Address'
        ];

        $result = $this->checkoutForm->setShippingAddress($params);
        $this->assertInstanceOf(EasyCheckoutForm::class, $result);
    }

    public function testSetBillingAddress()
    {
        $params = [
            'name' => 'John Doe',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'address' => 'Sample Billing Address'
        ];

        $result = $this->checkoutForm->setBillingAddress($params);
        $this->assertInstanceOf(EasyCheckoutForm::class, $result);
    }

    public function testSetBasketItem()
    {
        $items = [
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
        ];

        $result = $this->checkoutForm->setBasketItem($items);
        $this->assertInstanceOf(EasyCheckoutForm::class, $result);
    }

    public function testPaymentForm()
    {
        $paramsForm = [
            'conversation_id' => '123456789',
            'price' => '100.00',
            'paid_price' => '100.00',
            'basket_id' => 'B67832'
        ];
        $this->checkoutForm->setForm($paramsForm);

        $paramsBuyer = [
            'id' => 'BY789',
            'name' => 'John',
            'surname' => 'Doe',
            'phone' => '+905555555555',
            'email' => 'john.doe@example.com',
            'identity' => '12345678901',
            'address' => 'Sample Address',
            'ip' => '85.34.78.112',
            'city' => 'Istanbul',
            'country' => 'Turkey'
        ];
        $this->checkoutForm->setBuyer($paramsBuyer);

        $paramsShipping = [
            'name' => 'John Doe',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'address' => 'Sample Shipping Address'
        ];
        $this->checkoutForm->setShippingAddress($paramsShipping);

        $paramsBilling = [
            'name' => 'John Doe',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'address' => 'Sample Billing Address'
        ];
        $this->checkoutForm->setBillingAddress($paramsBilling);

        $items = [
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
        ];
        $this->checkoutForm->setBasketItem($items);

        $response = $this->checkoutForm->paymentForm();
        $this->assertNotEmpty($response, "The payment form should not be empty.");
    }
}
