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
GUANYI_API_TIME_OUT=2

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
    $model = Guanyi::getProducts ('Product Code');

    // get products, $products->data presents all of product list
    $model = Guanyi::getProducts ();
    
    // checkout whether success or not
    if ($products->success)
    {
    
        // go through list models
        foreach ($model->data as $index => $product)
        {
            // get product fields $product->xxx
        }
        
    } else {
    
        dd ($model->errorCodel); // error code
        dd ($model->errorDesc); // error message
        dd ($model->subErrorDesc); // additional error message
        dd ($model->requestMethod); // guanyi ERP method name

    }
}


```

```php


Route::get('/guanyi/products', function () {

    // get products by code, with page_no=1, and page_size=9999
    $model = Goodcatch\Guanyi\Facades\Guanyi::getProducts ('Product Code', [], 1, 9999);
    
    if ($model->success)
    {
        return $model->data;
    } else {
        
        // error message from guanyi api
        // for example: page_size=9999 is out of given range 1~99
        return $model->errorDesc;
    }
    
});

// got library exceptions
$model = Goodcatch\Guanyi\Facades\Guanyi::getProducts ();

if (! $model->success && isset ($model->exception) && is_array ($model->exception))
{
    foreach ($model->exception as $ex)
    {
        // Note: Increace GUANYI_API_TIME_OUT while keep getting exception.
        // string $ex
    }

}




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

采购管理

#### getPurchases

采购订单查询（gy.erp.purchase.get）





Licensed under [The MIT License (MIT)](LICENSE).

