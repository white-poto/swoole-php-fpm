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
        $messages = array(
            new Record\Stdout("hello world"),
            new Record\Stdout(),
            new Record\EndRequest(),
        );

        return join('', $messages);
    }
}