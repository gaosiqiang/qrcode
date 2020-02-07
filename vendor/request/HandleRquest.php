<?php
namespace vendor\request;

/**
 * 简单php 路由解析
 * Class HandleRquest
 * @package app\request
 */
class HandleRquest
{

    public $request = [];
    public $request_method = '';
    public $request_errors = [];
    public $request_method_map = [
        'POST' => 'post',
        'GET' => 'get',
    ];

    public function __construct()
    {
        $this->request = $_SERVER;
        $this->request_method = $this->request['REQUEST_METHOD'];
        $request_url = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        if ($request_url) {
            $request_array = explode('/', $request_url);
            array_map(function ($n) use(&$request_array){
                //是否会有脚本
                if (strpos($n, '.') !== false) {
                    array_shift($request_array);
                    if (strpos($n, '.php') !== false) {
                        //不属于php脚本
                        $this->request_errors[] = ['code' => 100001, 'msg' => '非php脚本'];
                    }
                }}, $request_array);
            $this->request = $request_array;
        } else {
            $this->request = [];
        }
    }

    /**
     * 获取控制器
     * @return mixed
     */
    public function getController()
    {
        return isset($this->request[0]) ? $this->request[0] : 'index';
    }

    /**
     * 获取action
     * @return mixed
     */
    public function getAction()
    {
        return isset($this->request[1]) ? $this->request[1] : 'index';
    }

    public function get($key, $value)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $value;
    }

    public function post($key, $value)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $value;
    }
}