<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/9
 * Time: 11:59
 */

namespace Jenner\Swoole\PHPFPM\Cache;


use Jenner\Swoole\PHPFPM\FCGIRequest;

interface CacheInterface
{

    /**
     * @param $key
     * @return mixed
     */
    public function has($key);

    /**
     * @param $key
     * @return FCGIRequest
     */
    public function get($key);

    /**
     * @param $key
     * @param FCGIRequest $request
     * @return mixed
     */
    public function set($key, FCGIRequest $request);

    /**
     * @param $key
     * @return mixed
     */
    public function delete($key);
}