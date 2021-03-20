<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$database = [
    [
        "id" => 10,
        "name" => "Room A"
    ],
    [
        "id" => 2,
        "name" => "Room B"
    ]
];

$app->get('/rooms/{id}', function (Request $request, Response $response, $args) {
    //parametry z url
    $id = $args['id'];
    $orderBy = $request->getQueryParams()['orderBy'];

    //vzdy odpovedet
    $response->getBody()->write('This is room with ID='. $id .' ordered by'. $orderBy);

    return $response;
});

$app->run();