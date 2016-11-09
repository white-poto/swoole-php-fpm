<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/9
 * Time: 11:57
 */

namespace Jenner\Swoole\PHPFPM\Cache;


use Jenner\Swoole\PHPFPM\FCGIRequest;
use Psr\Cache\CacheItemPoolInterface;

class ArrayCache implements CacheInterface
{
    protected $data = array();

    /**
     * @param $key
     * @return FCGIRequest|boolean
     */
    public function get($key)
    {
        if(array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return false;
    }

    /**
     * @param $key
     * @param FCGIRequest $request
     * @return mixed
     */
    public function set($key, FCGIRequest $request)
    {
        $this->data[$key] = $request;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        unset($this->data[$key]);
    }
}