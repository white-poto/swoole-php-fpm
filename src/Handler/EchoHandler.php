<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 11:00
 */

namespace Jenner\Swoole\PHPFPM\Handler;


use Protocol\FCGI\Record;

class EchoHandler implements HandlerInterface
{
    public function handle(Record $record)
    {
        var_dump($record);
        $data = "Status: 200\r\nContent-type: text/html\r\nHeader: value\r\n\r\nHello world";

        $messages = array(
            new Record\Stdout($data),
            new Record\Stdout(),
            new Record\EndRequest(),
        );

        return join('', $messages);
    }
}