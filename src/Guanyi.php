<?php


/*
 *
 *
 * (c) Allen, Li <morningbuses@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Goodcatch\Guanyi;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Collection;

/**
 * according to @see link(http://gop.guanyierp.com/hc/kb/category/1005768/ api_doc)
 * make http post requests to each one of Guanyi open ERP api
 * and then convert to Guanyi @see link(Goodcatch\Guanyi\Model Model)
 *
 * Class Guanyi
 *
 * @package Goodcatch\Guanyi
 * @author Allen, Li
 */
class Guanyi
{

    /**
     * @var mixed API URL
     */
    private $url;

    /**
     * @var mixed app key
     */
    private $key;

    /**
     * @var mixed app secret
     */
    private $secret;

    /**
     * @var mixed app session key
     */
    private $session;

    /**
     * @var Client http client
     */
    private $client;


    /**
     * Guanyi constructor.
     * @param array $config guanyi config
     */
    public function __construct(array $config)
    {
        $this->key = $config ['appkey'];
        $this->secret = $config ['appsecret'];
        $this->session = $config ['sessionkey'];
        $this->url = $config ['url'];

        $this->client = new Client([
            'timeout' => $config ['timeout'],
        ]);
    }

    /**
     * @param Request $request
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function exec(Request $request): Model
    {
        $result = null;
        try {
            $response = $this->client->send($request);
            if (!is_null($response) && !empty ($response) && $response->getStatusCode() === 200) {
                $body = $response->getBody();

                $result = $this->handleResp(\GuzzleHttp\json_decode($body->getContents(), true));
            }
        } catch (RequestException $e) {
            $result ['exception'] = [Psr7\str($e->getRequest())];
            if ($e->hasResponse()) {
                $result ['exception'] [] = Psr7\str($e->getResponse());
            }
        }
        return $result;
    }

    /**
     * 模型转换
     *
     * @param array $result
     * @return Model
     */
    private function handleResp(array $result): Model
    {
        return new Model($result);
    }

    private function transform (Model $model, $collection):Model
    {
        $transform = new Model;
        $transform->total = $model->total;
        $transform->data = $collection ?? \collect ([]);
        return $transform;
    }

    /**
     * make request
     *
     * @param string $method
     * @param array|null $req
     * @param int $page_no
     * @param int $page_size
     * @return Request
     */
    private function request(string $method, array $req = null, int $page_no = 1, int $page_size = 10): Request
    {
        if (is_null($req)) {
            $req = [];
        }
        $req ['appkey'] = $this->key;
        $req ['sessionkey'] = $this->session;
        $req ['method'] = $method;
        $req ['page_no'] = $page_no;
        $req ['page_size'] = $page_size;
        $req ['sign'] = $this->signature($req);
        $data_string = $this->json_encode_ch($req);
        $data_string = urlencode($data_string);
        $body = \GuzzleHttp\Psr7\stream_for($data_string);
        return new Request('POST', $this->url, [
            'Content-Type' => 'application/json;charset=utf-8',
            'Content-Length:' . strlen($data_string)
        ], $body);
    }

    /**
     * Get model by parameters
     *
     * @param string $method
     * @param array $params
     * @param int $page_no
     * @param int $page_size
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getModel (string $method, array $params = [], int $page_no = 1, int $page_size = 10): Model
    {

        return $this->exec(
            $this->request($method, $params, $page_no, $page_size)
        );
    }

    /**
     * Get model by parameter key-value
     *
     * @param string $method
     * @param string|null $param_key
     * @param string|null $param_val
     * @param array $params
     * @param int $page_no
     * @param int $page_size
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getModelByParameter (string $method, string $param_key = null, string $param_val = null, array $params = [], int $page_no = 1, int $page_size = 10): Model
    {
        if (! is_null($param_val) && ! is_null ($param_key))
        {
            $params [$param_key] = $param_val;
        }
        return $this->getModel($method, $params, $page_no, $page_size);
    }

    /**
     * Get model by parameter name 'code'
     *
     * @param string $method
     * @param string|null $code
     * @param array $params
     * @param int $page_no
     * @param int $page_size
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getModelByCode (string $method, string $code = null, array $params = [], int $page_no = 1, int $page_size = 10): Model
    {
        return $this->getModelByParameter($method, 'code', $code, $params, $page_no, $page_size);
    }







    // ********************************************************************
    // ************************* 基础信息 **********************************
    // ********************************************************************






    /**
     * 获取店铺列表
     *
     * @param string $code 店铺代码
     * @param array $params
     * @param int $page_no 页数
     * @param int $page_size 页数据数量
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getShops(string $code = null, array $params = [], int $page_no = 1, int $page_size = 10): Model
    {
        $model = $this->getModelByCode('gy.erp.shop.get', $code, $params, $page_no, $page_size);

        return $this->transform ($model, $model->shops);
    }


    /**
     * 获取仓库列表
     * @param string $code 店铺代码
     * @param array $params
     * @param int $page_no 页数
     * @param int $page_size 页数据数量
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getWarehouses(string $code = null, array $params = [], int $page_no = 1, int $page_size = 10): Model
    {
        $model = $this->getModelByCode('gy.erp.warehouse.get', $code, $params, $page_no, $page_size);

        return $this->transform ($model, $model->warehouses);
    }






    // ********************************************************************
    // ************************* 商品管理 **********************************
    // ********************************************************************








    /**
     * 获取商品列表
     *
     * @param string $code 商品代码
     * @param array $params
     * @param int $page_no 页数
     * @param int $page_size 页数据数量
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProducts(string $code = null, array $params = [], int $page_no = 1, int $page_size = 10): Model
    {
        $model = $this->getModelByCode('gy.erp.items.get', $code, $params, $page_no, $page_size);

        return $this->transform ($model, $model->items);
    }




    // ********************************************************************
    // ************************* 采购管理 **********************************
    // ********************************************************************




    /**
     * 根据采购订单编号获取采购订单列表
     *
     * @param string $code 采购订单编号
     * @param array $params
     * @param int $page_no 页数
     * @param int $page_size 页数据数量
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPurchases(string $code = null, array $params = [], int $page_no = 1, int $page_size = 10): Model
    {
        $model = $this->getModelByCode('gy.erp.purchase.get', $code, $params, $page_no, $page_size);
        return $this->transform ($model, $model->purchaseOrderList);
    }

    /**
     * 根据仓库代码获取采购订单列表
     *
     * @param string $warehouse_code 仓库代码
     * @param array $params
     * @param int $page_no 页数
     * @param int $page_size 页数据数量
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPurchasesByWarehouseCode(string $warehouse_code, array $params = [], int $page_no = 1, int $page_size = 10): Model
    {
        $model= $this->getModelByParameter('gy.erp.purchase.get', 'warehouse_code', $warehouse_code, $params, $page_no, $page_size);

        return $this->transform ($model, $model->purchaseOrderList);
    }

    /**
     * 根据供应商代码获取采购订单列表
     *
     * @param string $supplier_code 供应商代码
     * @param array $params
     * @param int $page_no 页数
     * @param int $page_size 页数据数量
     * @return Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPurchasesBySupplierCode(string $supplier_code, array $params = [], int $page_no = 1, int $page_size = 10): Model
    {

        $model= $this->getModelByParameter('gy.erp.purchase.get', 'supplier_code', $supplier_code, $params, $page_no, $page_size);

        return $this->transform ($model, $model->purchaseOrderList);
    }






    /**
     * 签名
     *
     * @param $data
     * @return string
     */
    private function signature(array $data): string
    {
        if (is_array($data) && count($data) > 0) {
            unset($data['sign']); //可选，具体看传参
            $data = $this->json_encode_ch($data);
            $sign = strtoupper(md5($this->secret . $data . $this->secret));
            return $sign;
        } else {
            return '';
        }
    }

    /**
     * 还原json字符串
     * @param $arr
     * @return string
     */
    private function json_encode_ch($arr)
    {
        return urldecode(json_encode($this->url_encode_arr($arr)));
    }

    /**
     * 转义url参数
     * @param $arr
     * @return array|string
     */
    private function url_encode_arr($arr)
    {
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                $arr[$k] = $this->url_encode_arr($v);
            }
        } elseif (!is_numeric($arr) && !is_bool($arr)) {
            $arr = urlencode($arr);
        }
        return $arr;
    }

}
