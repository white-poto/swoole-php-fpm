<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 11:00
 */

namespace Jenner\Swoole\PHPFPM\Handler;


use Protocol\FCGI\Record;

class DumpHandler implements HandlerInterface
{
    public function handle(Record $record)
    {
        var_dump($record);
    }
}