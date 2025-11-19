<?php

require_once __DIR__ . '/../config/connect.php';
require_once __DIR__ .'/../config/session-bootstrap.php';

$route = $_GET['route'] ?? 'home';

switch ($route) {
    case'register':
        include __DIR__ . '/../app/controllers/registerController.php';
        $controller = new RegisterController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register($_POST);
        } else {
            $controller->showRegisterForm();
        }
        break;
    case 'login':
        include __DIR__ . '/../app/controllers/loginController.php';
        $controller = new LoginController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;
            $controller->login($username, $password);
        } else {
            $controller->showLoginForm();
        }
        break;
    case 'google-login':
        require_once __DIR__ . '/../vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        
        $client = new Google\Client;

        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri('http://localhost/mywebsite/public/index.php?route=google-callback');
        $client->addScope("email");
        $client->addScope("profile");
        $client->addScope("openid");

        $authUrl = $client->createAuthUrl();
        header('Location: ' . $authUrl);
        exit();
        break;
    case 'google-callback':
        require_once __DIR__ . '/../vendor/autoload.php';
        include __DIR__ . '/../app/controllers/orderController.php';

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $controller = new OrderController($pdo);

        $client = new Google\Client;

        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri('http://localhost/mywebsite/public/index.php?route=google-callback');

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);

            $oauth = new Google\Service\Oauth2($client);
            $googleUser = $oauth->userinfo->get();

            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $googleUser->email);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['city'] = $user['city'] ?? null;
                $_SESSION['district'] = $user['district'] ?? null;
                $_SESSION['address'] = $user['address'] ?? null;
                $_SESSION['phone'] = $user['phone'] ?? null;
                $_SESSION['cart'] = $controller->getAllCarts($user['id']);
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (username, name, email, role) VALUES (:username, :name, :email, 'buyer')");
                $stmt->bindParam(':username', $googleUser->email);
                $stmt->bindParam(':name', $googleUser->name);
                $stmt->bindParam(':email', $googleUser->email);
                $stmt->execute();
                $_SESSION['id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $googleUser->email;
                $_SESSION['name'] = $googleUser->name;
                $_SESSION['role'] = 'buyer';
                $_SESSION['email'] = $googleUser->email;
                $_SESSION['city'] = '';
                $_SESSION['district'] = '';
                $_SESSION['address'] = '';
                $_SESSION['phone'] = '';
                $_SESSION['cart'] = $controller->getAllCarts($user['id']);
            }

            header('Location: index.php?route=home');
            exit();
        }
        break;
    case 'facebook-login':
        // Facebook login logic to be implemented
        require_once __DIR__ . '/../vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $fb = new \Facebook\Facebook([
            'app_id' => $_ENV['FACEBOOK_CLIENT_ID'],
            'app_secret' => $_ENV['FACEBOOK_CLIENT_SECRET'],
            'default_graph_version' => 'v16.0',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'public_profile']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('http://localhost/mywebsite/public/index.php?route=facebook-callback', $permissions);

        header('Location: ' . $loginUrl);
        exit();
        break;
    case 'facebook-callback':
        require_once __DIR__ . '/../vendor/autoload.php';
        include __DIR__ . '/../app/controllers/orderController.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $controller = new OrderController($pdo);

        $fb = new \Facebook\Facebook([
            'app_id' => $_ENV['FACEBOOK_CLIENT_ID'],
            'app_secret' => $_ENV['FACEBOOK_CLIENT_SECRET'],
            'default_graph_version' => 'v16.0',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            die('Graph returned an error: ' . $e->getMessage());
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            die('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if (!isset($accessToken)) {
            die('No access token received.');
        }

        try {
            $response = $fb->get('/me?fields=id,name,first_name,email', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            die('Graph returned an error: ' . $e->getMessage());
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            die('Facebook SDK returned an error: ' . $e->getMessage());
        }

        $fbUser = $response->getGraphUser();
        $firstName = $fbUser->getFirstName();
        $email = $fbUser->getEmail();
        $name = $fbUser->getName();

        // Kiểm tra DB theo email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['city'] = $user['city'] ?? '';
            $_SESSION['district'] = $user['district'] ?? '';
            $_SESSION['address'] = $user['address'] ?? '';
            $_SESSION['phone'] = $user['phone'] ?? '';
            $_SESSION['cart'] = $controller->getAllCarts($user['id']);
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, name, email, role) VALUES (:username, :name, :email, 'buyer')");
            $stmt->bindParam(':username', $email);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $_SESSION['id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = 'buyer';
            $_SESSION['email'] = $email;
            $_SESSION['city'] = '';
            $_SESSION['district'] = '';
            $_SESSION['address'] = '';
            $_SESSION['phone'] = '';
            $_SESSION['cart'] = $controller->getAllCarts($user['id']);
        }

        header('Location: index.php?route=home');
        exit();
        break;
    case 'logout':
        include __DIR__ . '/../app/controllers/logoutController.php';
        $controller = new LogoutController();
        $controller->logout();
        header("Location: index.php?route=home");
        break;
    case 'home':
        include __DIR__ . '/../app/controllers/homeController.php';
        $controller = new HomeController($pdo);
        $controller->show();
        break;
    case 'all-products':
        include __DIR__ . '/../app/controllers/productController.php';
        $controller = new ProductController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $type = $_GET['type'] ?? null;
            $filterApplied = [
                'Manufacturer' => $_GET['Manufacturer'] ?? null,
                'Brand' => $_GET['Brand'] ?? null,
                'Series' => $_GET['Series'] ?? null
            ];
            $PriceRange = [
                'min_price' => $_GET['min_price'] ?? null,
                'max_price' => $_GET['max_price'] ?? null
            ];
            $SortBy = $_GET['sortby'] ?? null;
            $controller->getProductsByType($type, $filterApplied, $PriceRange, $SortBy);
        }
        break;
    case 'view-detail':
        include __DIR__ . '/../app/controllers/productController.php';
        $controller = new ProductController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_GET['id'] ?? null;
            $controller->getProductById($id);
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if (isset($_POST['action']) && $_POST['action'] === 'edit-product') {
                $imageDir = __DIR__ . '/../storage/uploads/';
                $typename = strtolower($controller->getTypeName($_POST['type_id'] ?? null));
                $imageDir .= $typename . '/';
                if (!is_dir($imageDir)) {
                    mkdir($imageDir, 0755, true);
                }
                $file = $_FILES['Pimage'] ?? null;
                if(isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $file['tmp_name'];
                    $originalName = basename($file['name']);
                    $targetPath = $imageDir . $originalName;
                    move_uploaded_file($tmpName, $targetPath);
                    $_POST['Pimage'] = $typename . '/' . $originalName;
                } else {
                    $_POST['Pimage'] = null;
                }
                $controller->editProduct($id, $_POST);
            } else{
                // Handle adding to cart logic here
                $controller->addToCart($id);
                // For now, just redirect back to the product detail page
            }
            header("Location: index.php?route=view-detail&id=" . urlencode($id));
            exit();
        }
        break;
    case 'search':
        include __DIR__ . '/../app/controllers/searchController.php';
        $controller = new SearchController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $query = $_GET['q'] ?? null;
            $SortBy = $_GET['sortby'] ?? null;
            $controller->searchProducts($query, $SortBy);
        }
        break;
    case 'live-search':
        include __DIR__ . '/../app/controllers/searchController.php';
        $controller = new SearchController($pdo);
        $query = $_GET['q'] ?? '';
        $controller->searchAjax($query);
        break;
    case 'profile-overview':
        include __DIR__ . '/../app/controllers/userController.php';
        $controller = new UserController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateProfile($_POST);
        } else {
            $controller->showProfile();
        }
        break;
    case 'my-orders':
        // To be implemented
        include __DIR__ . '/../app/controllers/orderController.php';
        $controller = new OrderController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $filterApplied = [
                'Status' => $_GET['Status'] ?? null,
            ];
            $PriceRange = [
                'min_price' => $_GET['min_price'] ?? null,
                'max_price' => $_GET['max_price'] ?? null
            ];
            $DateRange = [
                'start_date' => $_GET['start_date'] ?? null,
                'end_date' => $_GET['end_date'] ?? null
            ];
            $exception = ['in_cart'];
            $SortBy = $_GET['sortby'] ?? null;
            $controller->getOrders($_SESSION['id'], $filterApplied, $PriceRange, $DateRange, $SortBy, $exception);
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;
            if ($action === 'cancel') {
                $orderId = $_POST['order_id'] ?? null;
                $controller->changeStatus($_SESSION['id'], 'Cancel Awaiting', $orderId);
                header("Location: index.php?route=my-orders");
                exit();
            }
            else if ($action === 'placed') {
                $orderId = $_POST['order_id'] ?? null;
                $controller->changeStatus($_SESSION['id'], 'Placed', $orderId);
                header("Location: index.php?route=my-orders");
                exit();
            }
        }
        break;
    case 'view-cart':
        include __DIR__ . '/../app/controllers/orderController.php';
        $controller = new OrderController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['id']) && isset($_POST['quantity'])) {
                $id = $_POST['id'];
                $quantity = $_POST['quantity'];
                $controller->updateCartQuantity($_SESSION['id'], $id, $quantity);
                $total = $controller->getCartTotal($_SESSION['id']);
                $total = is_null($total) ? 0 : (float) $total;

                echo json_encode(['total' => $total]);
                exit();
            } else if(isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
                $id = $_POST['id'];
                $controller->removeFromCart($_SESSION['id'], $id);
                header('Location: index.php?route=view-cart');
                exit();
            }else if(isset($_POST['action']) && $_POST['action'] === 'confirm') {
                $controller->changeStatus($_SESSION['id'], $_POST['status']);
                header('Location: index.php?route=my-orders&sortby=date_desc');
                exit();
            }
        }
        if(isset($_SESSION['id'])) {
            $controller->showCart($_SESSION['id']); 
        } else {
            header('Location: index.php?route=login');
            exit();
        }
        break;
    case 'admin-incoming-orders':
        include __DIR__ .'/../app/controllers/adminController.php';
        $controller = new AdminController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->getIncomingOrders($_GET['sortby'] ?? null);
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;
            if ($action === 'cancel') {
                $orderId = $_POST['order_id'] ?? null;
                $controller->changeStatus($_POST['user_id'], 'Cancelled', $orderId);
                header("Location: index.php?route=admin-incoming-orders");
                exit();
            }
        }
        break;
    case 'users-management':
        include __DIR__ . '/../app/controllers/adminController.php';
        $controller = new AdminController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $searchByName = $_GET['searchByName'] ?? null;
            $searchById = $_GET['searchById'] ?? null;
            $controller->showUsers($searchByName, $searchById);
        }
        break;
    case 'user-detail':
        include __DIR__ . '/../app/controllers/adminController.php';
        $controller = new AdminController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userId = $_GET['id'] ?? null;
            $searchByOrderId = $_GET['searchByOrderId'] ?? null;
            $filterApplied = [
                'Status' => $_GET['Status'] ?? null,
            ];
            $PriceRange = [
                'min_price' => $_GET['min_price'] ?? null,
                'max_price' => $_GET['max_price'] ?? null
            ];
            $DateRange = [
                'start_date' => $_GET['start_date'] ?? null,
                'end_date' => $_GET['end_date'] ?? null
            ];
            $exception = ['in_cart'];
            $SortBy = $_GET['sortby'] ?? null;
            $controller->showUserDetail($userId, $filterApplied, $PriceRange, $DateRange, $SortBy, $exception, $searchByOrderId);
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;
            if ($action === 'cancel') {
                $orderId = $_POST['order_id'] ?? null;
                $userId = $_POST['user_id'] ?? null;
                $controller->changeStatus($userId, 'Cancelled', $orderId);
                header("Location: index.php?route=user-detail&id=" . urlencode($userId));
                exit();
            }
            else if ($action === 'ship') {
                $orderId = $_POST['order_id'] ?? null;
                $userId = $_POST['user_id'] ?? null;
                $controller->changeStatus($userId, 'Shipped', $orderId);
                header("Location: index.php?route=user-detail&id=" . urlencode($userId));
                exit();
            }
            else if ($action === 'delivered') {
                $orderId = $_POST['order_id'] ?? null;
                $userId = $_POST['user_id'] ?? null;
                $controller->changeStatus($userId, 'Delivered', $orderId);
                header("Location: index.php?route=user-detail&id=" . urlencode($userId));
                exit();
            }
        }
        break;
    case 'product-category':
        include __DIR__ . '/../app/controllers/adminController.php';
        $controller = new AdminController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categoryId = $_GET['category_id'] ?? null;
            $categoryName = $_GET['category_name'] ?? null;
            $controller->getCategories($categoryId, $categoryName);
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;
            if (isset($action) && $action === 'add-category') {
                $categoryName = $_POST['category_name'] ?? null;
                $controller->addCategory($categoryName);
                header("Location: index.php?route=product-category");
                exit();
            }
        }
        break;
    case 'product-type':
        include __DIR__ . '/../app/controllers/adminController.php';
        $controller = new AdminController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categoryId = $_GET['category_id'] ?? null;
            $typeId = $_GET['type_id'] ?? null;
            $typeName = $_GET['type_name'] ?? null;
            $controller->getTypesByCategory($categoryId, $typeName, $typeId);
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;
            if (isset($action) && $action === 'add-type') {
                $categoryId = $_POST['category_id'] ?? null;
                $typeName = $_POST['type_name'] ?? null;
                $controller->addType($categoryId, $typeName);
                header("Location: index.php?route=product-type&category_id=" . urlencode($categoryId));
                exit();
            }
        }
        break;
    case 'view-products-by-type':
        include __DIR__ . '/../app/controllers/adminController.php';
        $controller = new AdminController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $type = $_GET['type_name'] ?? null;
            $filterApplied = [
                'Manufacturer' => $_GET['Manufacturer'] ?? null,
                'Brand' => $_GET['Brand'] ?? null,
                'Series' => $_GET['Series'] ?? null
            ];
            $PriceRange = [
                'min_price' => $_GET['min_price'] ?? null,
                'max_price' => $_GET['max_price'] ?? null
            ];
            $SortBy = $_GET['sortby'] ?? null;
            $controller->getProductsByType($type, $filterApplied, $PriceRange, $SortBy);
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;
            if (isset($action) && $action === 'add-product') {
                $imageDir = __DIR__ . '/../storage/uploads/';
                $typename = strtolower($controller->getTypeName($_POST['type_id'] ?? null));
                $imageDir .= $typename . '/';
                if (!is_dir($imageDir)) {
                    mkdir($imageDir, 0755, true);
                }
                $file = $_FILES['Pimage'] ?? null;
                if(isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $file['tmp_name'];
                    $originalName = basename($file['name']);
                    $targetPath = $imageDir . $originalName;
                    move_uploaded_file($tmpName, $targetPath);
                    $_POST['Pimage'] = $typename . '/' . $originalName;
                } else {
                    $_POST['Pimage'] = null;
                }
                $productData = [
                    'Pname' => $_POST['Pname'] ?? null,
                    'Pdescription' => $_POST['Pdescription'] ?? null,
                    'Pimage' => $_POST['Pimage'] ?? null,
                    'price' => $_POST['price'] ?? null,
                    'series' => $_POST['series'] ?? null,
                    'manufacturer' => $_POST['manufacturer'] ?? null,
                    'brand' => $_POST['brand'] ?? null,
                    'type_id' => $_POST['type_id'] ?? null,
                    'category_id' => $_POST['category_id'] ?? null
                ];
                $type = $_POST['type_name'] ?? null;
                $controller->addProduct($productData);
                header("Location: index.php?route=view-products-by-type&type_name=" . urlencode($type) ."&category_id=" . urlencode($productData['category_id']) . "&page=" . urlencode($_GET['page'] ?? 1));
                exit();
            }
        }
        break;
    case 'change-product-stock':
        include __DIR__ . '/../app/controllers/ProductController.php';
        $controller = new ProductController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $storeName = $_POST['store_name'] ?? null;
            $productId = $_POST['id'] ?? null;
            $newStock = $_POST['quantity'] ?? null;
            $controller->changeProductStock($productId, $storeName, $newStock);
            header("Location: index.php?route=view-detail&id=" . urlencode($productId));
            exit();
        }
        break;
}