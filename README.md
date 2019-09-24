Guanyi ERP API Library
======

Make Guanyi ERP API easier

## Installation

```
composer require goodcatch/guanyierp

php artisan vendor:publish --tag guanyi-config
```

## Configuration

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


## Usage

For examples:

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

// use criteria, note that get started with 'query' before 'critieria'
Goodcatch\Guanyi\Facades\Guanyi::query ()
    ->criteria ('start_date', '2019-09-24 00:00:00')
    ->criteria ('end_date', '2019-09-25 23:59:59')
    // use httpclient
    ->setHttpClient (new GuzzleHttp\Client ([
        // options
        'timeout' => 2
    ]))
    ->getProducts ([
        // overrite criteria
        'start_date' => '2019-09-25 00:00:00'
    ]);




```


## Methods

### 基础信息

#### 店铺查询（gy.erp.shop.get）

##### getShops
获取店铺列表

##### getWarehouses
获取仓库列表

---

### 商品管理

#### 商品查询（gy.erp.items.get）

##### getProducts
获取商品列表
 
---

### 采购管理

#### 采购订单查询（gy.erp.purchase.get）

##### getPurchases
获取采购订单列表

##### getPurchasesByWarehouseCode
根据仓库代码获取采购订单列表

##### getPurchasesBySupplierCode
根据供应商代码获取采购订单列表


---

### 订单管理

#### 发货单查询（gy.erp.trade.deliverys.get）

##### getTradeDeliverys
获取发货单列表

##### getTradeDeliverysByCode

根据单据编号获取发货单列表

##### getTradeDeliverysByWarehouse

根据仓库代码获取发货单列表

##### getTradeDeliverysByShop

根据店铺代码获取发货单列表

##### getTradeDeliverysByOuter

根据平台单号获取发货单列表







Licensed under [The MIT License (MIT)](LICENSE).

