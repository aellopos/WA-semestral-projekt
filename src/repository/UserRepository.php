<?php

namespace App\Repository;

use DI\Container;
use Firebase\JWT\JWT;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct(Container $container)
    {
        $this->db = $container->get('db');
    }

    public function getById(int $id): array | bool
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id_users=:id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): array | bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users
                (login, email, password, name, surname, gender, registered, role)
            VALUES 
                (:login, :email, :password, :name, :surname, :gender, :registered, :role)
        ");

        $stmt->bindValue(':login', $data['login']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':surname', $data['surname']);
        $stmt->bindValue(':gender', $data['gender']);
        $stmt->bindValue(':registered', time());
        $stmt->bindValue(':role', $data['role']);

        try {
            $stmt->execute();
            $id = $this->db->lastInsertId();
            return $this->getById($id);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function verifyLogin(string $login, string $password): array | bool
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE login=:login");
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user !== false) {
            $hash = $user["password"];
            if (password_verify($password, $hash)) {
                return $user;
            }
        }

        return false;
    }

    public function createToken(int $userId): string
    {
        $tokenKey = $_ENV['TOKEN_KEY'];
        $tokenPayload = [
            "userId" => $userId,
        ];
        return JWT::encode($tokenPayload, $tokenKey, "HS256");
    }
}