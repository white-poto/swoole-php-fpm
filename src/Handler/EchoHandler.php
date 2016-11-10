<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 11:00
 */

namespace Jenner\Swoole\PHPFPM\Handler;


use Jenner\Swoole\PHPFPM\FCGIRequest;
use Protocol\FCGI;
use Protocol\FCGI\Record;

class EchoHandler implements HandlerInterface
{
    public function handle(FCGIRequest $request)
    {
        var_dump($request);
        $body     = var_export($request, true); // let's response with content of all FCGI params from the request
        $bodySize = strlen($body);

        /** @var Record[] $messages */
        $messages = [
            // we can also split responses into several chunks for streaming large response
            new Record\Stdout("HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\nContent-Length: {$bodySize}\r\n\r\n{$body}"),
            new Record\Stdout(''), // empty one, according to the specification
            new Record\EndRequest(FCGI::REQUEST_COMPLETE, $appStatus = 0), // normal request termination
        ];
        $responseContent = '';
        foreach ($messages as $message) {
            $message->setRequestId($request->getRequestId());
            $responseContent .= $message;
        }

        return $responseContent;
    }
}