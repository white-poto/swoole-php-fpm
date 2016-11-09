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
        $body = file_get_contents("http://qidian.qpic.cn/qidian_common/349573/c6307e16b36ee882c844f38103628c65/0");
        $length = strlen($body);
        $data = "HTTP/1.1 200 OK\r\nServer: X2S_Platform\r\nConnection: keep-alive\r\nDate: Tue, 08 Nov 2016 08:22:43 GMT\r\nCache-Control: max-age=2592000\r\nExpires: Thu, 08 Dec 2016 08:22:43 GMT\r\nLast-Modified: Mon, 07 Nov 2016 11:35:21 GMT\r\nContent-Type: image/jpeg\r\nContent-Length: {$length}\r\nKeep-Alive: timeout=60\r\nX-Cache-Lookup: Hit From Disktank\r\n\r\n";
        $data .= $body;

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