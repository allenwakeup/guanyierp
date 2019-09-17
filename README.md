Guanyi ERP API PHP SDK
======
Make Guanyi ERP API easier

### Installation

```
composer require goodcatch/guanyierp

php artisan vendor:publish --tag guanyi-config
```

### Configuration

In Configuration file `config/guanyi.php`, checkout following configuration 


```
'auth' => [
    'appkey' => env('GUANYI_API_APP_KEY', ''),
    'sessionkey' => env('GUANYI_API_SESSION_KEY', ''),
    'secret' => env('GUANYI_API_SECRET', '')
]
```

and then add environment key-values to `.env` file

```ini
GUANYI_API_APP_KEY=xxxxxxx
GUANYI_API_SESSION_KEY=xxxxxxx
GUANYI_API_SECRET=xxxxxxx

```


Licensed under [The MIT License (MIT)](LICENSE).

