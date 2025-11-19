<?php

include __DIR__ . '/../models/userModel.php';
include __DIR__ . '/../models/productModel.php';

class RegisterController {
    private $userModel;
    private $productModel;

    public function __construct($pdo) {
        $this->userModel = new UserModel($pdo);
        $this->productModel = new ProductModel($pdo);
    }

    public function showRegisterForm() {
        // Show the registration form
        $error_register = $_SESSION['error'] ?? null;
        $_SESSION['error'] = null;
        $categories = $this->productModel->getAllCategories();
        $types = $this->productModel->getType();
        $grouped = [];
        foreach ($categories as $cat) {
            $grouped[$cat['Cname']] = []; 
        }

        foreach ($types as $t) {
            foreach ($categories as $cat) {
                if ($t['category_id'] == $cat['Cid']) {
                    $grouped[$cat['Cname']][] = $t['Tname'];
                }
            }
        }
        include __DIR__ .'/../../resources/register.php';
    }

    public function register($data) {
        // Register a new user
        $password = $data['password'];
        $confirmed_password = $data['confirmed_password'];
        if ($password === $confirmed_password) {
            try{
                $userID = $this->userModel->createUser($data);

                echo "Registration successful!";

                session_regenerate_id(true);
                $user = $this->userModel->getUserById($userID);

                $_SESSION['id'] = $userID;
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = 'buyer';
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['city'] = $user['city'] ?? null;
                $_SESSION['district'] = $user['district'] ?? null;
                $_SESSION['address'] = $user['address'] ?? null;
                $_SESSION['phone'] = $user['phone'] ?? null;

                header("location: index.php?route=home");
                exit();
                // Handle password mismatch
            } catch (Exception $e) {
                $_SESSION['error'] = "Registration failed: " . $e->getMessage();
                header("location: index.php?route=register&error=" . urlencode($_SESSION['error']));
                exit();
            }
        } else {
            $_SESSION['error'] = "Passwords do not match.";
            header("location: index.php?route=register&error=" . urlencode($_SESSION['error']));
            exit();
        }
    }
}