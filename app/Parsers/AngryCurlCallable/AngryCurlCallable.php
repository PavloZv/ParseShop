<?php

namespace App\Parsers\AngryCurlCallable;

/**
 * Class AngryCurlCallable
 * @package App\Parsers\AngryCurlCallable
 */
class AngryCurlCallable
{
    /**
     * @var
     */
    private $response = [];

    /**
     * @var
     */
    private $info = [];

    /**
     * @var
     */
    private $request = [];

    /**
     * @param $response
     * @param $info
     * @param $request
     */
    public function callback_function($response, $info, $request)
    {
        $this->response[$info['url']] = $response;
        $this->info[] = $info;
        $this->request[] = $request;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

}