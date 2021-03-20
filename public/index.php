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

$app->get('/rooms/{id}', function (Request $request, Response $response, $args) use ($database) {
    //parametry z url
    $id = $args['id'];
    $rooms = array_filter($database, fn($room) => $room["id"]== $id);

    if (count($rooms) > 0) {
        $room = $rooms[array_key_first($rooms)];
        $json = json_encode($room);

        $response->getBody()->write($json);
        return $response;
    } else {
        return $response->withStatus(404, 'Room not found');
    }

    return $response;
});

$app->run();