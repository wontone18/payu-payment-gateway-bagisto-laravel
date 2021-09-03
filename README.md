# Bagisto Payu Payment Gateway
Payu is a popular payment gateway in india. This package provides a additional strong help for the user to use the payu payment gateway in their Bagisto laravel ecommerce application.

## Automatic Installation
1. Use command prompt to run this package `composer require wontonee/payu`
2. Now open `config/app.php` and register payu provider.
```sh
'providers' => [
        // Payu provider
        Wontonee\Payu\Providers\PayuServiceProvider::class,
]
```
3. Now go to `package/Webkul/Admin/src/Resources/lang/en` copy these line at the bottom end of code.
```sh
 'payu-merchant-key'                      => 'Merchant Key',
 'payu-salt-key'                      => 'Salt Key',
 'payu-websitestatus'                      => 'Sandbox/Live',
```
4. Now run `php artisan config:cache`
5. Now go to your bagisto admin section `admin/configuration/sales/paymentmethods` you will see the new payment gateway payu. 
6. Now open `app\Http\Middleware\VerifyCsrfToken.php` and add this route to the exception list.
```sh
protected $except = [
                  '/payu-success',
                  '/payu-failure'
           ];

```
7. Now open the command prompt and run `composer dump-autoload`.

## Manual Installation
1. Download the zip folder from the github repository.
2. Unzip the folder and go to your bagisto application path `package` and create a folder name `Wontonee/Payu/` upload `src` folder inside this path.
3. Now open `config/app.php` and register payu provider.
```sh
'providers' => [
        // Payu provider
        Wontonee\Payu\Providers\PayuServiceProvider::class,
]
```
4. Now open composer.json and go to `autoload psr-4`.
```sh
"autoload": {
        "psr-4": {
        "Wontonee\\Payu\\": "packages/Wontonee/Payu/src"
        }
    }
```
5. Now go to `package/Webkul/Admin/src/Resources/lang/en` copy these line at the bottom end of code.
```sh
 'payu-merchant-key'                      => 'Merchant Key',
 'payu-salt-key'                      => 'Salt Key',
 'payu-websitestatus'                      => 'Sandbox/Live',
```
6. Now open the command prompt and run `composer dump-autoload`.
7. Now run `php artisan config:cache`
9. Now go to your bagisto admin section `admin/configuration/sales/paymentmethods` you will see the new payment gateway payu. 
9. Now open `app\Http\Middleware\VerifyCsrfToken.php` and add this route to the exception list.
```sh
protected $except = [
                  '/payu-success',
                  '/payu-failure'
           ];

```

For any help or customisation  <https://www.wontonee.com> or email us <hello@wontonee.com>
