<?php

include __DIR__ . '/../models/userModel.php';
include __DIR__ . '/../models/orderModel.php';
include __DIR__ . '/../models/productModel.php';

class LoginController {
    private $userModel;
    private $orderModel;
    private $productModel;


    public function __construct($pdo) {
        $this->userModel = new UserModel($pdo);
        $this->orderModel = new OrderModel($pdo);
        $this->productModel = new ProductModel($pdo);
    }

    public function showLoginForm() {
        // Show the login form
        $error_login = $_SESSION['error'] ?? null;
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
        include __DIR__ .'/../../resources/login.php';
    }

    public function login($username, $password) {
        // Authenticate user
        $user = $this->userModel->authenticateUser($username, $password);

        if ($user) {
            session_regenerate_id(true);

            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['city'] = $user['city'] ?? '';
            $_SESSION['district'] = $user['district'] ?? '';
            $_SESSION['address'] = $user['address'] ?? '';
            $_SESSION['phone'] = $user['phone'] ?? '';
            $_SESSION['cart'] = $this->orderModel->getAllCarts($user['id']);

            header("location: index.php?route=home");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header("location: index.php?route=login&error=" . urlencode($_SESSION['error']));
            exit();
        }
    }
}