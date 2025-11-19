<?php

class UserModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function createUser($data) {
        $checkstmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $checkstmt->bindParam(":username", $data['username'], PDO::PARAM_STR);
        $checkstmt->execute();
        $count_username = $checkstmt->fetchColumn();
        if ($count_username > 0){
            throw new Exception('Username already exists !');
        }
        
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        $user = $this->getUserByEmail($data['email']);
        if(isset($user['id'])) {
            if (isset($user['password_hash'])) {
                throw new Exception('Email already registered !');
            }
            else {
                $stmt = $this->pdo->prepare("UPDATE users SET password_hash = :password_hash, username = :username WHERE email = :email");
                $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
                $stmt->bindParam(":password_hash", $passwordHash, PDO::PARAM_STR); 
                $stmt->bindParam(":username", $data['username'], PDO::PARAM_STR); 
                $stmt->execute();
                return $user['id'];
            }
        }

        $stmt = $this->pdo->prepare("INSERT INTO users (username, name, email, password_hash, role) VALUES (:username, :name, :email, :password_hash, 'buyer')");
        $stmt->bindParam(":username", $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(":name", $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(":password_hash", $passwordHash, PDO::PARAM_STR);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    // Add user-related methods here
    public function authenticateUser($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($password, $user["password_hash"])) {
            return $user;
        }
        return false;
    }

    public function updateUserContactInfo($userId, $data) {
        $stmt = $this->pdo->prepare("UPDATE users SET city = :city, district = :district, address = :address, phone = :phone WHERE id = :id");
        $stmt->bindParam(":city", $data['city'], PDO::PARAM_STR);
        $stmt->bindParam(":district", $data['district'], PDO::PARAM_STR);
        $stmt->bindParam(":address", $data['address'], PDO::PARAM_STR);
        $stmt->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getAllUsers($searchByName, $searchById) {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];
        if (!empty($searchByName)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%" . $searchByName . "%";
        }
        if (!empty($searchById)) {
            $sql .= " AND id = ?";
            $params[] = $searchById;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}