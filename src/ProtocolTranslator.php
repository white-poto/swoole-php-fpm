<?php
/**
 * Created by PhpStorm.
 * User: huyanping
 * Date: 2016/11/10
 * Time: 10:47
 */

namespace Jenner\Swoole\PHPFPM;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Protocol\FCGI;
use Protocol\FCGI\Record;

class ProtocolTranslator
{
    public static function translateRequest(FCGIRequest $cgi_request) {
        $params = $cgi_request->getParams();
        $method = $params['REQUEST_METHOD'];
        $uri = $params['REQUEST_URI'];
        $headers = self::getHeaders($params);
        $body = $cgi_request->getStdin();
        $version = self::getProtocolVersion($params);

        return new Request($method, $uri, $headers, $body, $version);
    }

    public static function translateResponse($request_id, Response $http_response) {
        $status = $http_response->getStatusCode();
        $version = $http_response->getProtocolVersion();
        $headers = self::headersToString($http_response->getHeaders());
        $body = $http_response->getBody();

        $message = "HTTP/{$version} {$status}\r\n{$headers}\r\n\r\n{$body}";

        /** @var Record[] $messages */
        $messages = [
            // we can also split responses into several chunks for streaming large response
            new Record\Stdout($message),
            new Record\Stdout(''), // empty one, according to the specification
            new Record\EndRequest(FCGI::REQUEST_COMPLETE, $appStatus = 0), // normal request termination
        ];
        $responseContent = '';
        foreach ($messages as $message) {
            $message->setRequestId($request_id);
            $responseContent .= $message;
        }

        return $responseContent;
    }

    private static function headersToString(array $headers) {
        $result = '';
        foreach($headers as $key=>$value) {
            $result .= $key .': ' . $value;
        }

        return $result;
    }

    private static function getProtocolVersion(array $params) {
        $version_string = $params['SERVER_PROTOCOL'];
        $info = explode('/', $version_string);
        return $info[1];
    }

    private static function getHeaders(array $params) {
        $headers = array();
        foreach($params as $key=>$value) {
            if(strpos($key, 'HTTP') === 0) {
                $name = str_replace('_', '-', ucwords($value));
                $headers[$name] = $value;
            }
        }

        return $headers;
    }

}