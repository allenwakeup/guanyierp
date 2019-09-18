<?php


namespace Goodcatch\Guanyi;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Guanyi
 * @package Goodcatch\Guanyi
 * @author Allen, Li
 */
class Guanyi
{

    private $key;

    private $secret;

    private $session;

    private $client;


    public function __construct(array $config)
    {
        $this->key = $config ['appkey'];
        $this->secret = $config ['appsecret'];
        $this->session = $config ['sessionkey'];

        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $config ['url'],
            // You can set any number of default request options.
            'timeout'  => $config ['timeout'],
        ]);
    }

    public function exec (Request $request):array
    {
        $result = [];
        try {
            $response = $this->client->send($request);
            if (is_string ($response) && ! empty ($response))
            {
                $result = $this->handleResp(\GuzzleHttp\json_decode($response));
            }
        } catch (RequestException $e) {
            $result ['exception'] = [Psr7\str($e->getRequest())];
            if ($e->hasResponse()) {
                $result ['exception'] [] = Psr7\str($e->getResponse());
            }
        }
        return $result;
    }

    private function handleResp (array $result): array
    {
        return $result;
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
    private function request (string $method, array $req = null, int $page_no = 1, int $page_size = 10):Request
    {
        if (is_null ($req))
        {
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
        return new Request('POST', '/', ['body' => $body]);
    }


    /**
     * 获取商品列表
     *
     * @param string $code 商品代码
     * @return array
     */
    public function getProducts (string $code = null):array
    {
        return $this->exec(
            $this->request('gy.erp.items.get', is_null ($code) ? null : ['code' => $code])
        );
    }

    /**
     * 签名
     *
     * @param $data
     * @return string
     */
    private function signature(array $data):string {
        if (is_array($data) && count($data) > 0) {
            return '';
        }
        unset($data['sign']); //可选，具体看传参
        $data = $this->json_encode_ch($data);
        $sign = strtoupper(md5($this->secret . $data . $this->secret));
        return $sign;
    }

    /**
     * 还原json字符串
     * @param $arr
     * @return string
     */
    private function json_encode_ch($arr) {
        return urldecode(json_encode($this->url_encode_arr($arr)));
    }

    /**
     * 转义url参数
     * @param $arr
     * @return array|string
     */
    private function url_encode_arr($arr) {
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
