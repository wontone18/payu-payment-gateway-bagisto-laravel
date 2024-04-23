# Bagisto Payu Payment Gateway
Payu is a popular payment gateway in india. This package provides a additional strong help for the user to use the payu payment gateway in their Bagisto laravel ecommerce application.

## Installation
1. Use the command prompt to install this package:
```sh
composer require wontonee/payu
```

2. Open `config/app.php` and register the Payu provider.
```sh
'providers' => [
        // Payu provider
        Wontonee\Payu\Providers\PayuServiceProvider::class,
]
```
3. Navigate to the `admin panel -> Configure/Payment Methods`, where Payu will be visible at the end of the payment method list.

4. Now open `app\Http\Middleware\VerifyCsrfToken.php` and add this route to the exception list.
```sh
protected $except = [
                  '/payu-success',
                  '/payu-failure'
           ];
```

5. Now run 
```sh
php artisan config:cache
```


## Troubleshooting

1. if anybody facing after placing a order you are not redirecting to payment gateway and getting a route error then simply go to `bootstrap/cache` and delete all the cache files.

For any help or customisation  <https://www.wontonee.com> or email us <hello@wontonee.com>
