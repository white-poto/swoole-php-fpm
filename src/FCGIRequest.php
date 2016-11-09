<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/9
 * Time: 09:44
 */

namespace Jenner\Swoole\PHPFPM;


class FCGIRequest
{
    public $id = null;
    public $role = 0;
    public $flags = 0;
    public $params = [];
    public $stdin = '';
}