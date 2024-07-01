# TIVENTS webhook receiver

Just a small receiver based filament to test webhooks from TIVENTS

## Used packages

* [Laravel](https://beers.li/Z3AHg)
* [filament](https://beers.li/28doq) 
* [Webhook Client](https://beers.li/Du3jw) 
 
## Installation

Like every Laravel project

```php
composer install
```

### Publish the configs

```php
php artisan vendor:publish --provider="Spatie\WebhookClient\WebhookClientServiceProvider" --tag="webhook-client-config"
php artisan vendor:publish --tag="filament-webhook-client-config"
```

### Publish migration
```php
php artisan vendor:publish --provider="Spatie\WebhookClient\WebhookClientServiceProvider" --tag="webhook-client-migrations"
```

### Set webhook client secret
```php
php artisan receiver:set-signature-key
```
