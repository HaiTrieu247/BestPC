<?php

include __DIR__ . '/../models/productModel.php';
include __DIR__ . '/../models/userModel.php';

class UserController {
    private $productModel;
    private $userModel;

    public function __construct($pdo) {
        $this->productModel = new ProductModel($pdo);
        $this->userModel = new UserModel($pdo);
    }

    // Additional methods for user-related actions can be added here
    public function showProfile() {
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

        include __DIR__ . '/../../resources/profile-overview.php';
    }

    public function updateProfile($data) {
        $userId = $data['id'] ?? null;
        $updatedData = [
            'city' => $data['city'] ?? '',
            'district' => $data['district'] ?? '',
            'address' => $data['address'] ?? '',
            'phone' => $data['phone'] ?? ''
        ];

        try {
            $this->userModel->updateUserContactInfo($userId, $updatedData);

            // Update session data
            $_SESSION['city'] = $updatedData['city'];
            $_SESSION['district'] = $updatedData['district'];
            $_SESSION['address'] = $updatedData['address'];
            $_SESSION['phone'] = $updatedData['phone'];

            header("location: index.php?route=profile-overview");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Update failed: " . $e->getMessage();
            header("location: index.php?route=profile-overview&error=" . urlencode($_SESSION['error']));
            exit();
        }
    }
}