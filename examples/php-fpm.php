<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 11:12
 */

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$server = new swoole_server('127.0.0.1', 9001);
$server->set(array(
    'reactor_num' => 2, //reactor thread num
    'worker_num' => 4,    //worker process num
    'backlog' => 128,   //listen backlog
    'max_request' => 50,
    'dispatch_mode' => 2,
));

$fpm = new \Jenner\Swoole\PHPFPM\FPM($server);
$fpm->start();