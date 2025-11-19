<?php

include __DIR__ . '/../models/orderModel.php';
include __DIR__ . '/../models/productModel.php';
include __DIR__ . '/../models/userModel.php';

class OrderController {
    private $orderModel;
    private $productModel;
    private $userModel;

    public function __construct($pdo) {
        $this->orderModel = new OrderModel($pdo);
        $this->productModel = new ProductModel($pdo);
        $this->userModel = new UserModel($pdo);
    }

    // Additional methods for order-related actions can be added here

    public function getAllCarts($userId){
        return $this->orderModel->getAllCarts($userId);
    }

    public function showCart($userId){
        $carts = $this->orderModel->showCart($userId);
        $totalPrice = 0;

        foreach($carts as $cart){
            $totalPrice += $cart['price'] * $cart['quantity'];
        }

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
        if (isset($_SESSION['id'])) {
            $_SESSION['cart'] = $this->orderModel->getAllCarts($_SESSION['id']);
        }

        include __DIR__ .'/../../resources/view-cart.php';
    }

    public function updateCartQuantity($userId, $productId, $quantity){
        $this->orderModel->updateCartQuantity($userId, $productId, $quantity);
        if (isset($_SESSION['id'])) {
            $_SESSION['cart'] = $this->orderModel->getAllCarts($_SESSION['id']);
        }
    }

    public function getCartTotal($userId){
        return $this->orderModel->getCartTotal($userId);
    }

    public function removeFromCart($userId, $productId){
        $this->orderModel->removeFromCart($userId, $productId);
        if (isset($_SESSION['id'])) {
            $_SESSION['cart'] = $this->orderModel->getAllCarts($_SESSION['id']);
        }
    }

    public function changeStatus($userId, $status, $orderId=null){
        $userData = $this->userModel->getUserById($userId);
        if ($status == 'Placed') {
           $this->orderModel->updateLocation($userId, $userData);
        }
        $this->orderModel->changeStatus($userId, $status, $orderId);
        if (isset($_SESSION['id'])) {
            $_SESSION['cart'] = $this->orderModel->getAllCarts($_SESSION['id']);
        }
    }
    public function getOrders($userId=null, $filterApplied, $PriceRange, $DateRange, $SortBy, $exception){
        // Placeholder for showing user orders
        $orders = $this->orderModel->getOrders($userId, $filterApplied, $PriceRange, $DateRange, $SortBy, $exception);
        
        $filter = [];

        $filter['Status'] = ['Placed', 'Shipped', 'Delivered', 'Canceled', 'Cancel Awaiting'];

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
        
        include __DIR__ . '/../../resources/my-orders.php';
    }
}