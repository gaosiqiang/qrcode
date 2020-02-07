<?php
/**
 * Created by PhpStorm.
 * User: sqtech
 * Date: 2018/11/14
 * Time: 5:02 PM
 */

namespace vendor\base;

use vendor\request\HandleRquest;

class Application
{
    public static $app = null;

    public $response = null;

    public $module = 'app\controller';

    public function run()
    {
        // TODO 错误处理机制，显示错误，目前不显示具体的错误很难调试
        static::$app = $this;
        //注册组件
        static::$app->request = new HandleRquest();
        //组织处理对象
        $controller = static::$app->request->getController();
        $controller = ucfirst($controller).'Controller';
        $controller = $this->module ."\\". $controller;
        $action = static::$app->request->getAction();
        $action = 'action'.ucfirst($action);
        //回调资源
        $this->response = call_user_func_array([new $controller(), $action], []); //调用对象里的方法并传参
        // TODO 回调before函数处理action返回的数据
        echo json_encode($this->response);
        exit();
    }

}