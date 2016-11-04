<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 10:53
 */

namespace Jenner\Swoole\PHPFPM\Handler;


use Protocol\FCGI\Record;

interface HandlerInterface
{
    public function handle(Record $record);
}