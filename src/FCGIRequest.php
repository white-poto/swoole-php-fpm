<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/9
 * Time: 09:44
 */

namespace Jenner\Swoole\PHPFPM;


class FCGIRequest
{
    private $request_id = null;
    private $role = 0;
    private $flags = 0;
    private $params = [];
    private $stdin = '';

    /**
     * FCGIRequest constructor.
     * @param null $request_id
     * @param int $role
     * @param int $flag
     * @param array $params
     * @param string $stdin
     */
    public function __construct($request_id = null, $role = 0, $flag = 0, array $params = array(), $stdin = '')
    {
        $this->request_id = $request_id;
        $this->role = $role;
        $this->flags = $flag;
        $this->params = $params;
        $this->stdin = $stdin;
    }

    /**
     * @return null
     */
    public function getRequestId()
    {
        return $this->request_id;
    }

    /**
     * @param null $request_id
     * @return $this
     */
    public function setRequestId($request_id)
    {
        $this->request_id = $request_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param int $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return int
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param int $flags
     * @return $this
     */
    public function setFlags($flags)
    {
        $this->flags = $flags;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function addParams(array $params)
    {
        foreach ($params as $key => $value) {
            $this->params[$key] = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getStdin()
    {
        return $this->stdin;
    }

    /**
     * @param string $stdin
     * @return $this
     */
    public function setStdin($stdin)
    {
        $this->stdin = $stdin;
        return $this;
    }

    /**
     * @param $stdin
     * @return $this
     */
    public function appendStdin($stdin)
    {
        $this->stdin .= $stdin;
        return $this;
    }
}