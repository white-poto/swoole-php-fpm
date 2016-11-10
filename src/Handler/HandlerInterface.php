<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/4
 * Time: 10:53
 */

namespace Jenner\Swoole\PHPFPM\Handler;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HandlerInterface
{
    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request);
}