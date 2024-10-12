<?php
namespace Iyzico\EasyCheckoutForm;

use Dotenv\Dotenv;
use Exception;
use Iyzipay\Options;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Request\RetrieveCheckoutFormRequest;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Currency;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\CheckoutForm;

class EasyCheckoutForm
{
    protected $options;
    protected $request;
    protected $basketItems;

    public function __construct(array $config = [])
    {
        // Eğer API bilgileri parametre olarak verilmemişse, .env dosyasından al
        if (empty($config)) {
            // .env dosyasını yükle
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->safeLoad();
            
            // .env dosyasından bilgileri al
            $apiKey = $_ENV['IYZIPAY_API_KEY'];
            $secretKey = $_ENV['IYZIPAY_SECRET_KEY'];
            $status = $_ENV['IYZIPAY_STATUS'];
            $callbackUrl = $_ENV['IYZIPAY_CALLBACK_URL'];
            $baseUrlTest = $_ENV['IYZIPAY_BASE_URL_TEST'];
            $baseUrlProduction = $_ENV['IYZIPAY_BASE_URL_PRODUCTION'];
        } else {
            // Parametreleri config dizisinden al
            $apiKey = $config['api_key'] ?? null;
            $secretKey = $config['secret_key'] ?? null;
            $status = $config['status'] ?? 'test';
            $callbackUrl = $config['callback_url'] ?? null;
            $baseUrlTest = $config['base_url_test'] ?? 'https://sandbox-api.iyzipay.com';
            $baseUrlProduction = $config['base_url_production'] ?? 'https://api.iyzipay.com';
        }

        if (!$apiKey || !$secretKey || !$callbackUrl) {
            throw new Exception("API key, secret key, and callback URL are required.");
        }

        // İlgili URL'yi belirle
        $baseUrl = ($status === 'test') ? $baseUrlTest : $baseUrlProduction;

        // Iyzico yapılandırmalarını ayarla
        $this->options = new Options();
        $this->options->setApiKey($apiKey);
        $this->options->setSecretKey($secretKey);
        $this->options->setBaseUrl($baseUrl);

        $this->request = new CreateCheckoutFormInitializeRequest();
        $this->request->setCallbackUrl($callbackUrl);
        $this->basketItems = [];
    }

    public function setForm(array $params)
    {
        if (!isset($params['conversation_id'], $params['price'], $params['paid_price'], $params['basket_id'])) {
            throw new Exception("Form parameters are missing or incomplete.");
        }

        $this->request->setLocale(Locale::TR);
        $this->request->setConversationId($params['conversation_id']);
        $this->request->setPrice($params['price']);
        $this->request->setPaidPrice($params['paid_price']);
        $this->request->setCurrency(Currency::TL);
        $this->request->setBasketId($params['basket_id']);
        $this->request->setPaymentGroup(PaymentGroup::PRODUCT);

        return $this;
    }

    public function setBuyer(array $params)
    {
        if (!isset($params['id'], $params['name'], $params['surname'], $params['phone'], $params['email'], $params['identity'], $params['address'], $params['ip'], $params['city'], $params['country'])) {
            throw new Exception("Buyer parameters are missing or incomplete.");
        }

        $buyer = new Buyer();
        $buyer->setId($params['id']);
        $buyer->setName($params['name']);
        $buyer->setSurname($params['surname']);
        $buyer->setGsmNumber($params['phone']);
        $buyer->setEmail($params['email']);
        $buyer->setIdentityNumber($params['identity']);
        $buyer->setRegistrationAddress($params['address']);
        $buyer->setIp($params['ip']);
        $buyer->setCity($params['city']);
        $buyer->setCountry($params['country']);
        $this->request->setBuyer($buyer);

        return $this;
    }

    public function setShippingAddress(array $params)
    {
        if (!isset($params['name'], $params['city'], $params['country'], $params['address'])) {
            throw new Exception("Shipping address parameters are missing or incomplete.");
        }

        $shippingAddress = new Address();
        $shippingAddress->setContactName($params['name']);
        $shippingAddress->setCity($params['city']);
        $shippingAddress->setCountry($params['country']);
        $shippingAddress->setAddress($params['address']);
        $this->request->setShippingAddress($shippingAddress);

        return $this;
    }

    public function setBillingAddress(array $params)
    {
        if (!isset($params['name'], $params['city'], $params['country'], $params['address'])) {
            throw new Exception("Billing address parameters are missing or incomplete.");
        }

        $billingAddress = new Address();
        $billingAddress->setContactName($params['name']);
        $billingAddress->setCity($params['city']);
        $billingAddress->setCountry($params['country']);
        $billingAddress->setAddress($params['address']);
        $this->request->setBillingAddress($billingAddress);

        return $this;
    }

    public function setBasketItem(array $items)
    {
        if (empty($items)) {
            throw new Exception("Basket items cannot be empty.");
        }

        foreach ($items as $value) {
            if ($value['price'] <= 0) {
                throw new Exception("Basket item price must be greater than zero.");
            }

            $basketItem = new BasketItem();
            $basketItem->setId($value['id']);
            $basketItem->setName($value['name']);
            $basketItem->setCategory1($value['category']);
            $basketItem->setItemType(BasketItemType::PHYSICAL);
            $basketItem->setPrice($value['price']);
            array_push($this->basketItems, $basketItem);
        }
        $this->request->setBasketItems($this->basketItems);

        return $this;
    }

    public function paymentForm()
    {
        $form = CheckoutFormInitialize::create($this->request, $this->options);
        return $form;
    }

    public function verifyPayment($token)
    {
        if (empty($token)) {
            throw new Exception("Payment token is required for verification.");
        }

        $request = new RetrieveCheckoutFormRequest();
        $request->setLocale(Locale::TR);
        $request->setToken($token);

        $checkoutForm = CheckoutForm::retrieve($request, $this->options);

        if ($checkoutForm->getStatus() == "success") {
            return [
                'status' => true,
                'message' => $checkoutForm->getErrorMessage(),
                'paymentStatus' => $checkoutForm->getPaymentStatus(),
                'token' => $checkoutForm->getToken(),
                'paymentId' => $checkoutForm->getPaymentId(),
                'currency' => $checkoutForm->getCurrency(),
                'price' => $checkoutForm->getPrice(),
                'paidPrice' => $checkoutForm->getPaidPrice(),
                'rawData' => $checkoutForm->getRawResult()
            ];
        } else {
            return [
                'status' => false,
                'message' => $checkoutForm->getErrorMessage(),
                'rawData' => $checkoutForm->getRawResult()
            ];
        }
    }
}
