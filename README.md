# Bagisto Payu Payment Gateway
Payu is a popular payment gateway in India. This package provides strong support for users to integrate the Payu payment gateway into their Bagisto Laravel e-commerce applications.

## Licensing Information

You must take a license from the website [https://myapps.wontonee.com/login](https://myapps.wontonee.com/login), either trial or paid.

- **Trial License**: Works for 7 days.
- **Paid License**: Valid for 1 year and costs only ₹400.  
  Includes updates and support.

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

1. If you encounter an issue where you are not redirected to the payment gateway after placing an order and receive a route error, navigate to `bootstrap/cache` and delete all cache files.


For any help or customization, visit <https://www.wontonee.com> or email us <dev@wontonee.com>
