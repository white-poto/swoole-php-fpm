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
        $content = "hello world";
        $length = strlen($content);
        $data = "Status: 200\r\nConetent-Type: text/html\r\nContent-Length: {$length}\r\n\r\n{$content}";

        $messages = array(
            new Record\Stdout($data),
            new Record\Stdout(),
            new Record\EndRequest(),
        );
        foreach ($messages as $message) {
            $message->setRequestId($record->getRequestId());
        }

        return join('', $messages);
    }
}