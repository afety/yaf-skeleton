<?php

namespace Library\ExtInterfaces;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use http\QueryString;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractApi
{
    /**
     * 请求基地址
     * @var string
     */
    protected $host = '';

    /**
     * 请求固定header头
     * @var array
     */
    protected $headers = [];

    /**
     * 请求方法
     */
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /**
     * 文件上传 如$data = fopen(xxx)
     * @param string $uri
     * @param resource $data
     * @param array $headers
     * @param array $options
     * @return ResponseInterface
     */
    protected function sendPostResource(string $uri, $data = null, array $headers = [], array $options = [])
    {
        return $this->sendPost($uri, ['body' => $data], $headers, $options);
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param array $options
     * @return ResponseInterface
     */
    protected function sendPostForm(string $uri, array $data = [], array $headers = [], array $options = [])
    {
        return $this->sendPost($uri, ['form_params' => $data], $headers, $options);
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param array $options
     * @return ResponseInterface
     */
    protected function sendPostJson(string $uri, array $data = [], array $headers = [], array $options = [])
    {
        return $this->sendPost($uri, ['json' => $data], $headers, $options);
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param array $options
     * @return ResponseInterface
     */
    protected function sendPost(string $uri, array $data = [], array $headers = [], array $options = [])
    {
        return $this->send(self::METHOD_POST, $uri,
            array_merge(['headers' => $headers, 'form_params' => $data], $options));
    }


    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param array $options
     * @return ResponseInterface
     */
    protected function sendGet(string $uri, array $data = [], array $headers = [], array $options = [])
    {
        return $this->send(self::METHOD_GET, $uri,
            array_merge(['headers' => $headers, 'query' => $data], $options));
    }

    /**
     * 请求发送
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    protected function send(string $method, string $uri, array $options)
    {
        $client = new Client(['base_uri' => $this->host]);
        $response = $client->request($method, $uri, $options);

        return $response;
    }
}