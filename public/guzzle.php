<?php

require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;

// Create a React event loop
$loop = Factory::create();

// Create a Guzzle handler that integrates with React
$handler = new CurlMultiHandler();
$timer = $loop->addPeriodicTimer(0, \Closure::bind(function () use (&$timer) {
    $this->tick();
    if (empty($this->handles) && Promise\queue()->isEmpty()) {
        $timer->cancel();
    }
}, $handler, $handler));

// Create a Guzzle client that uses our special handler
$client = new Client([
    'handler' => HandlerStack::create($handler),
]);

// Send a request and handle the response asynchronously
$client->getAsync('http://loripsum.net/api')
->then(function (ResponseInterface $response) {
    echo 'Response: '.$response->getBody();
});

// Run everything to completion!
$loop->run();