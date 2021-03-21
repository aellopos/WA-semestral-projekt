<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RoomController {

    private RoomRepository $repository;

    public function __construct(RoomRepository $repository) {
        $this->repository = $repository;
    }

    public function getAll(ResponseInterface $response): ResponseInterface {
        $rooms = $this->repository->getAll();
        $json = json_encode($rooms);
        $response->getBody()->write($json);
        return $response;
    }

    public function getById(ResponseInterface $response, int $id): ResponseInterface {
        $room = $this->repository->getById($id);

        if ($room !== false) {
            $json = json_encode($room);
            $response->getBody()->write($json);
            return $response;
        } else {
            return $response->withStatus(404, 'Not Found');
        }
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $room = json_decode($request->getBody(), true);
        $tokenPayload = $request->getAttribute('token');
        $userId = (int) $tokenPayload['userId'];

        if ($room !== null and !empty($room["title"]) and $userId) {
            $room = $this->repository->create($room, $userId);
            $json = json_encode($room);
            $response->getBody()->write($json);
            return $response->withStatus(201, 'Created');
        } else {
            return $response->withStatus(400, 'Bad input');
        }
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, int $id): ResponseInterface
    {
        $room = $this->repository->getById($id);
        $updatedRoom = json_decode($request->getBody(), true);

        if (empty($updatedRoom["title"])) {
            return $response->withStatus(400, 'Bad input');
        } else if ($room !== false) {
            $updatedRoom = $this->repository->update($id, $updatedRoom);
            $json = json_encode($updatedRoom);
            $response->getBody()->write($json);
            return $response->withStatus(202, 'Updated');
        } else {
            return $response->withStatus(404, 'Not Found');
        }
    }

    public function delete(ResponseInterface $response, int $id): ResponseInterface
    {
        $this->repository->delete($id);
        return $response->withStatus(204);
    }

}