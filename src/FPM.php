<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 10:31
 */

namespace Jenner\Swoole\PHPFPM;


use Jenner\Swoole\PHPFPM\Handler\HandlerInterface;
use Protocol\FCGI;
use Protocol\FCGI\FrameParser;
use Protocol\FCGI\Record;
use Protocol\FCGI\Record\BeginRequest;
use Protocol\FCGI\Record\Params;
use Protocol\FCGI\Record\Stdin;

class FPM
{
    protected $server;
    protected $handler;

    protected $cache;
    protected $requests;

    public function __construct(\swoole_server $server, HandlerInterface $handler = null)
    {
        $this->server = $server;
        if(!is_null($handler)) {
            $this->handler = $handler;
        }
        $this->parser = new FrameParser();
    }

    public function registerHandler(HandlerInterface $handler) {
        $this->handler = $handler;
    }

    public function start() {
        $this->server->on('connect', function (\swoole_server $server, $id) {
            $this->requests[$id] = new FCGIRequest();
        });

        $this->server->on('receive', function(\swoole_server $server, $id, $from_id, $data) {
            while (FrameParser::hasFrame($data)) {
                $message = FrameParser::parseFrame($data);

                if ($message instanceof BeginRequest) {
                    $this->requests[$id]->id    = $message->getRequestId();
                    $this->requests[$id]->role  = $message->getRole();
                    $this->requests[$id]->flags = $message->getFlags();
                }
                if ($message instanceof Params) {
                    $this->requests[$id]->params += $message->getValues();
                }
                if ($message instanceof Stdin) {
                    $isLastParam = $message->getContentLength() == 0;
                    if (!$isLastParam) {
                        $this->requests[$id]->stdin .= $message->getContentData();
                    } else {
                        $this->onRequest($server, $id);
                    }
                }
            }
        });
        $this->server->on('close', function (\swoole_server $server, $fd, $from_id) {
            unset($this->cache[$fd]);
        });
        $this->server->start();
    }

    protected function onRequest(\swoole_server $server, $id) {
        $request  = $this->requests[$id];
        $body     = print_r($request, true); // let's response with content of all FCGI params from the request
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
            $message->setRequestId($request->id);
            $responseContent .= $message;
        }
        $server->send($id, $responseContent);
        $server->close($id);
    }
}