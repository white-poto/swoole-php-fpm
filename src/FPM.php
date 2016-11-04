<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 10:31
 */

namespace Jenner\Swoole\PHPFPM;


use Jenner\Swoole\PHPFPM\Handler\HandlerInterface;
use Protocol\FCGI\FrameParser;

class FPM
{
    protected $server;
    protected $handler;

    protected $cache;

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
        $this->server->on('receive', function(\swoole_server $server, $fd, $from_id, $data) {
            $this->cache[$fd] .= $data;
            if(FrameParser::hasFrame($this->cache[$fd])) {
                $record = FrameParser::parseFrame($this->cache[$fd]);
                $this->handler->handle($record);
            }
        });
        $this->server->on('close', function (\swoole_server $server, $fd, $from_id) {
            unset($this->cache[$fd]);
        });
        $this->server->start();
    }
}