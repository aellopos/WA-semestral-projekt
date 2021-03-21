<?php

use App\Controller\HomeController;
use App\Controller\RoomController;
use App\Controller\UserController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    // Homepage
    $app->get('/', [HomeController::class, 'index']);

    // Login and registration
    $app->post('/login', [UserController::class, 'login']);
    $app->post('/registration', [UserController::class, 'register']);

    // --- Secured routes ---
    $app->group('/auth', function(RouteCollectorProxy $group) {

        // Rooms
        $group->get('/rooms', [RoomController::class, 'getAll']);
        $group->get('/rooms/{id}', [RoomController::class, 'getById']);
        $group->post('/rooms', [RoomController::class, 'create']);
        $group->put('/rooms/{id}', [RoomController::class, 'update']);
        $group->delete('/rooms/{id}', [RoomController::class, 'delete']);

    });
    // --- end of Secured routes ---

    // CORS
    // - always respond successfully to options
    // - set up CORS headers - allow access from frontend on a different domain
    $app->options('/{routes:.+}', fn (ResponseInterface $response) => $response);
    $app->add(function (ServerRequestInterface $request, $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

    // Not Found - handle all other routes as 404 Not Found error
    $app->map(['GET', 'POST', 'PUT', 'DELETE'], '/{routes:.+}', fn (ResponseInterface $response) =>
        $response->withStatus(404, 'Not Found')
    );

};