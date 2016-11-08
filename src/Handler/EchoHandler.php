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
        $body = "Hello world";
        $length = strlen($body);
        $data = "Status: 200\r\nContent-Length: {$length}\r\n\r\n{$body}";

        $messages = array(
            new Record\Stdout($data),
            new Record\Stdout(),
            new Record\EndRequest(),
        );

        return join('', $messages);
    }
}