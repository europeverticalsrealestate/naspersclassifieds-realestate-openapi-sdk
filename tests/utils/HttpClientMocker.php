<?php
namespace naspersclassifieds\realestate\openapi\tests\utils;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class HttpClientMocker
{
    private $historyContainer = [];
    private $client;
    private $mockHandler;

    public function __construct()
    {
        $history = Middleware::history($this->historyContainer);
        $this->mockHandler = new MockHandler();
        $stack = HandlerStack::create($this->mockHandler);
        $stack->push($history);
        $this->client = new Client(['handler' => $stack]);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Request|null
     */
    public function getLastRequest() {
        $lastElementInHistory = array_pop($this->historyContainer);
        return !empty($lastElementInHistory['request']) ? $lastElementInHistory['request'] : null;
    }

    /**
     * @return Response|null
     */
    public function getLastResponse() {
        $lastElementInHistory = array_pop($this->historyContainer);
        return !empty($lastElementInHistory['response']) ? $lastElementInHistory['response'] : null;
    }


    /**
     * @param Response $response
     */
    public function addResponse(Response $response)
    {
        $this->mockHandler->append($response);
    }
}