<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Relay\Relay;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Middlewares\FastRoute;
use function Fastroute\simpleDispatcher;
use FastRoute\RouteCollector;
use Middlewares\Whoops;

$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$request = $creator->fromGlobals();
// $response = (new Psr17Factory())->createResponse();

//Create the router dispatcher
$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/hello/{name}', function ($request) {
        //The route parameters are stored as attributes
        $name = $request->getAttribute('name');

        //You can echo the output (it will be captured and written into the body)
        echo sprintf('Hello %s', $name);

        //Or return a string
        return sprintf('Hello %s', $name);

        //Or return a response
        return new Response();
    });
});

$queue[] = new FastRoute($routes);
$queue[] = new Whoops();
$relay = new Relay($queue);
$relay->handle($request);
