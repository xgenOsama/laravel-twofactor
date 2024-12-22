```shell
composer require pragmarx/google2fa-laravel
# php artisan vendor:publish --provider="PragmaRX\Google2FA\Vendor\Laravel\ServiceProvider"
php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider"

composer require laravel/ui
php artisan ui bootstrap --auth
composer require bacon/bacon-qr-code 
```