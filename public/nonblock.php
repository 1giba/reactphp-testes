<?php
//Exemplo NonBlock
require '../vendor/autoload.php';

$app = function($request, $response) {
  $response->writeHead(200, array('Content-Type' => 'text/plain'));
  $response->end("Hello World\n");
};

// Abstraction for event loops (by default you don't need any external library).
$loop = React\EventLoop\Factory::create();
// Connects an socket to the event loop.
$socket = new React\Socket\Server($loop);

// Creates a HTTP server with an event subscriber listening
// to incoming requests.
$http = new React\Http\Server($socket, $loop);
$http->on('request', $app);

echo "Server running at http://127.0.0.1:9001\n";

$socket->listen(9001);
$loop->run();