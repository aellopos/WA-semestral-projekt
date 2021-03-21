<?php

namespace App\Repository;

use DI\Container;
use PDO;

class RoomRepository {

    private PDO $db;

    public function __construct(Container $container)
    {
        $this->db = $container->get('db');
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("select * from rooms");
        $stmt->execute();
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rooms;
    }

    public function getById(int $id): array | bool
    {
        $stmt = $this->db->prepare("select * from rooms where id_rooms=:id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data, int $userId = 0): array {
        $stmt = $this->db->prepare("insert into rooms (title, created, id_users_owner) values (:title,:created,:id_users_owner)");
        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':created', time());
        $stmt->bindValue(':id_users_owner', $userId);
        $stmt->execute();

        $id = $this->db->lastInsertId();

        return $this->getById($id);
    }

    public function update(int $id, array $data): array
    {
        $stmt = $this->db->prepare("UPDATE rooms SET title=:title WHERE id_rooms=:id_rooms");
        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':id_rooms', $id);
        $stmt->execute();
        return $this->getById($id);
    }

    public function delete(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM rooms WHERE id_rooms=:id_rooms");
        $stmt->bindValue(':id_rooms', $id);
        $stmt->execute();
    }
}