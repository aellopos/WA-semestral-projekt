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

$app->get('/rooms/{id}', function (Request $request, Response $response, $args) {
    //parametry z url
    $id = $args['id'];
    $orderBy = $request->getQueryParams()['orderBy'];

    //vzdy odpovedet
    $response->getBody()->write('This is room with ID='. $id .' ordered by'. $orderBy);

    return $response;
});

$app->get('/mars/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $orderBy = $request->getQueryParams()['orderBy'];

    $vozitka = ['MAR-A', 'MAR-B', 'MAR-C', 'ALPHA'];
    
    if(isset($orderBy)) {
        sort($vozitka);
        $response->getBody()->write('Stranka s vozitkem '. $vozitka[$id]);
    } else {
        $response->getBody()->write('Stranka s vozitkem '. $vozitka[$id]);
    }

    return $response;
});

$app->run();