<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 11:00
 */

namespace Jenner\Swoole\PHPFPM\Handler;


use GuzzleHttp\Psr7\Response;
use Jenner\Swoole\PHPFPM\FCGIRequest;
use Protocol\FCGI;
use Protocol\FCGI\Record;
use Psr\Http\Message\RequestInterface;

class EchoHandler implements HandlerInterface
{
    public function handle(RequestInterface $request)
    {
        $status = 200;
        $body = "test";
        $headers = array(
            'Content-Type' => 'text/plain',
            'Content-Length' => strlen($body),
        );

        $response = new Response(200, $headers, $body);
        return $response;
    }
}