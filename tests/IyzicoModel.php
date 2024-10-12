<?php
namespace App\Models;

use Dotenv\Dotenv;

class IyzicoModel
{
    public function __construct()
    {
        // Proje kök dizininden .env dosyasını yükleyin
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad(); // Dosyanın var olup olmadığını kontrol eder, yoksa hata vermez
    }

    public function getConfig()
    {
        return [
            'api_key' => $_ENV['IYZIPAY_API_KEY'] ?? 'default_api_key',
            'secret_key' => $_ENV['IYZIPAY_SECRET_KEY'] ?? 'default_secret_key',
            'status' => $_ENV['IYZIPAY_STATUS'] ?? 'test',
            'base_url_test' => $_ENV['IYZIPAY_BASE_URL_TEST'] ?? 'https://sandbox-api.iyzipay.com',
            'base_url_production' => $_ENV['IYZIPAY_BASE_URL_PRODUCTION'] ?? 'https://api.iyzipay.com',
            'callback_url' => $_ENV['IYZIPAY_CALLBACK_URL'] ?? 'https://yourdomain.com/callback'
        ];
    }
}
