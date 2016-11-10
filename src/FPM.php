<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 10:31
 */

namespace Jenner\Swoole\PHPFPM;


use Jenner\Swoole\PHPFPM\Cache\CacheInterface;
use Jenner\Swoole\PHPFPM\Exceptions\ProtocolException;
use Jenner\Swoole\PHPFPM\Handler\HandlerInterface;
use Protocol\FCGI\FrameParser;
use Protocol\FCGI\Record\BeginRequest;
use Protocol\FCGI\Record\Params;
use Protocol\FCGI\Record\Stdin;

class FPM
{
    protected $server;
    protected $handler;

    /**
     * @var CacheInterface
     */
    protected $cache;

    public function __construct(\swoole_server $server, HandlerInterface $handler = null)
    {
        $this->server = $server;
        if (!is_null($handler)) {
            $this->handler = $handler;
        }
        $this->parser = new FrameParser();
    }

    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function registerHandler(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public function start()
    {
        $this->server->on('receive', (new \ReflectionMethod($this, 'onReceive'))->getClosure($this));
        $this->server->start();
    }

    public function onReceive(\swoole_server $server, $id, $from_id, $data)
    {
        while (FrameParser::hasFrame($data)) {
            $message = FrameParser::parseFrame($data);

            if ($message instanceof BeginRequest) {
                $request_id = $message->getRequestId();
                if ($this->cache->has($request_id)) {
                    $request = $this->cache->get($request_id);
                    $this->cache->delete($request_id);
                    throw new ProtocolException($request->getRequestId());
                }
                $request = new FCGIRequest(
                    $message->getRequestId(),
                    $message->getRole(),
                    $message->getFlags());
                $this->cache->set($request_id, $request);
            }
            if ($message instanceof Params) {
                $request = $this->cache->get($message->getRequestId());
                $request->addParams($message->getValues());
                $this->cache->set($message->getRequestId(), $request);
            }
            if ($message instanceof Stdin) {
                $request_id = $message->getRequestId();
                $request = $this->cache->get($request_id);
                $isLastParam = $message->getContentLength() == 0;
                if (!$isLastParam) {
                    $request->appendStdin($message->getContentData());
                    $this->cache->set($message->getRequestId(), $request);
                } else {
                    $this->onRequest($id, $request);
                    $this->cache->delete($request_id);
                }
            }
        }
    }

    protected function onRequest($id, FCGIRequest $request)
    {
        $http_request = ProtocolTranslator::translateRequest($request);
        $response = $this->handler->handle($http_request);
        $message = ProtocolTranslator::translateResponse($request->getRequestId(), $response);
        $this->server->send($id, $message);
        if ($request->getFlags() != 1) {
            $this->server->close($id);
        }
    }
}