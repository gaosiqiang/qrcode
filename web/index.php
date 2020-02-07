<?php

//TODO config设置错误参数
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../vendor/base/Application.php';


(new vendor\base\Application())->run();

