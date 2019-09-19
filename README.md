Guanyi ERP API PHP Library
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

And then add environment key-values to `.env` file

```ini
GUANYI_API_APP_KEY=xxxxxxx
GUANYI_API_SESSION_KEY=xxxxxxx
GUANYI_API_SECRET=xxxxxxx

```

Also, the optional key-value `GUANYI_API` default to [API URL](http://v2.api.guanyierp.com/rest/erp_open)
can be set in others URLs

```ini
GUANYI_API=http://v2.api.guanyierp.com/rest/erp_open
```


### Usage

Everywhere, in Closure, Functions... etc. For examples:

```php


use Goodcatch\Guanyi\Facades\Guanyi;

public function xxx () {

    // get product by product code
    $product = Guanyi::getProducts('Product Code');

    // get products, $products->items presents all of product list
    $products = Guanyi::getProducts();
}


```

```php


Route::get('/guanyi/products', function () {
    $model = Goodcatch\Guanyi\Facades\Guanyi::getProducts();
    return $model->data;
});



```


### API definitions

基础信息

#### getShops

店铺查询（gy.erp.shop.get）

---

商品管理

#### getProducts

商品查询（gy.erp.items.get）
 
---




Licensed under [The MIT License (MIT)](LICENSE).

