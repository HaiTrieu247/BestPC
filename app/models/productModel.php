<?php

class ProductModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function countProducts() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM products");
        return $stmt->fetchColumn();
    }

    public function getAllProducts() {
        $sql = "
            SELECT 
                p.*,
                COUNT(o.order_id) AS total_orders
            FROM products p
            LEFT JOIN orders o ON p.Pid = o.product_id
        ";
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $sql .= " WHERE p.is_hidden = 0";
        }
        $sql .= "            
            GROUP BY p.Pid
            ORDER BY total_orders DESC
            ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getType(){
        $stmt = $this->pdo->query("SELECT * from types");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsByType($type, $filterApplied, $page = 1, $PriceRange, $SortBy) {
        $limit = 8;
        $offset = ($page - 1) * $limit;
        $sql1 = "SELECT COUNT(*) FROM products JOIN types ON products.type_id = types.Tid WHERE types.Tname = ?";
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $sql1 .= " AND products.is_hidden = 0";
        }
        $params = [$type];

        if (!empty($filterApplied['Brand'])){
            $placeholders = implode(',', array_fill(0, count($filterApplied['Brand']), '?'));
            $sql1 .= " AND products.brand_id IN (SELECT Bid FROM brands WHERE Bname IN ($placeholders))";
            $params = array_merge($params, $filterApplied['Brand']);
        }
        if (!empty($filterApplied['Manufacturer'])){
            $placeholders = implode(',', array_fill(0, count($filterApplied['Manufacturer']), '?'));
            $sql1 .= " AND products.manufacturer_id IN (SELECT Mid FROM manufacturers WHERE Mname IN ($placeholders))";
            $params = array_merge($params, $filterApplied['Manufacturer']);
        }
        if (!empty($filterApplied['Series'])){
            $placeholders = implode(',', array_fill(0, count($filterApplied['Series']), '?'));
            $sql1 .= " AND products.series_id IN (SELECT seid FROM series WHERE Sname IN ($placeholders))";
            $params = array_merge($params, $filterApplied['Series']);
        }
        if (!empty($PriceRange['min_price'])) {
            $sql1 .= " AND products.price >= ?";
            $params = array_merge($params, [$PriceRange['min_price']]);
        }
        if (!empty($PriceRange['max_price'])) {
            $sql1 .= " AND products.price <= ?";
            $params = array_merge($params, [$PriceRange['max_price']]);
        }

        $stmt = $this->pdo->prepare($sql1);
        
        $stmt->execute($params);
        $total = $stmt->fetchColumn();

        $sql2 = "SELECT products.* FROM products JOIN types ON products.type_id = types.Tid WHERE types.Tname = ?";

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $sql2 .= " AND products.is_hidden = 0";
        }

        if (!empty($filterApplied['Brand'])){
            $placeholders = implode(',', array_fill(0, count($filterApplied['Brand']), '?'));
            $sql2 .= " AND products.brand_id IN (SELECT Bid FROM brands WHERE Bname IN ($placeholders))";
        }
        if (!empty($filterApplied['Manufacturer'])){
            $placeholders = implode(',', array_fill(0, count($filterApplied['Manufacturer']), '?'));
            $sql2 .= " AND products.manufacturer_id IN (SELECT Mid FROM manufacturers WHERE Mname IN ($placeholders))";
        }
        if (!empty($filterApplied['Series'])){
            $placeholders = implode(',', array_fill(0, count($filterApplied['Series']), '?'));
            $sql2 .= " AND products.series_id IN (SELECT seid FROM series WHERE Sname IN ($placeholders))";
        }
        if (!empty($PriceRange['min_price'])) {
            $sql2 .= " AND products.price >= ?";
        }
        if (!empty($PriceRange['max_price'])) {
            $sql2 .= " AND products.price <= ?";
        }

        if ($SortBy == 'price_asc') {
            $sql2 .= " ORDER BY products.price ASC";
        } elseif ($SortBy == 'price_desc') {
            $sql2 .= " ORDER BY products.price DESC";
        }

        $sql2 .= " LIMIT $limit OFFSET $offset";

        $stmt = $this->pdo->prepare($sql2);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'products' => $products,
            'totalPages' => max(1, ceil($total / $limit)),
            'currentPage' => (int)$page
        ];
    }

    public function getBrandByType($type){
        $stmt = $this->pdo->prepare("SELECT * from brands WHERE Bid IN (SELECT brand_id FROM products JOIN types ON products.type_id = types.Tid WHERE types.Tname = :type)");
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getManufacturerByType($type){
        $stmt = $this->pdo->prepare("SELECT * from manufacturers WHERE Mid IN (SELECT manufacturer_id FROM products JOIN types ON products.type_id = types.Tid WHERE types.Tname = :type)");
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSeriesByType($type){
        $stmt = $this->pdo->prepare("SELECT * from series WHERE seid IN (SELECT series_id FROM products JOIN types ON products.type_id = types.Tid WHERE types.Tname = :type)");
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE Pid = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStoreAvailability($id) {
        $stmt = $this->pdo->prepare("
            SELECT stores.Sname, stores.Slocation, stores.Smap_url, IN_STOCK.quantity
            FROM stores
            JOIN IN_STOCK ON stores.Sid = IN_STOCK.store_id
            WHERE IN_STOCK.product_id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function viewByCategory($name) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE category_id = (SELECT Cid FROM categories WHERE Cname = :name)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories($categoryId = null, $categoryName = null){
        $params = [];
        $sql = "SELECT * FROM categories WHERE 1=1";
        if ($categoryId) {
            $sql .= " AND Cid = ?";
            $params[] = $categoryId;
        }
        if ($categoryName) {
            $sql .= " AND Cname LIKE ?";
            $params[] = "%$categoryName%";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function convertToType($type_id){
        $stmt = $this->pdo->prepare("SELECT Tname FROM types WHERE Tid = :type_id");
        $stmt->bindParam(':type_id', $type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function addProductToCart($productId, $userId) {
        // Placeholder for adding product to cart logic
        // This function can be expanded to actually add the product to the user's cart in the database
        $checkstmt = $this->pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = :user_id AND product_id = :product_id AND status = 'in_cart'");
        $checkstmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $checkstmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $checkstmt->execute();
        $exists = $checkstmt->fetchColumn() > 0;

        $price = $this->pdo->prepare("SELECT price FROM products WHERE Pid = :product_id");
        $price->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $price->execute();
        $productPrice = $price->fetchColumn();

        if (!$exists) {
            $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, product_id, price) VALUES (:user_id, :product_id, :price)");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':price', $productPrice, PDO::PARAM_STR);
            $stmt->execute();

        } else {
            $stmt = $this->pdo->prepare("UPDATE orders SET quantity = quantity + 1, price = price + :price WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':price', $productPrice, PDO::PARAM_STR);
            $stmt->execute();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [$productId];
        } else {
            $_SESSION['cart'][] = $productId;
        }
    }

    public function addCategory($categoryName){
        $stmt = $this->pdo->prepare("INSERT INTO categories (Cname) VALUES (:categoryName)");
        $stmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getTypesByCategory($categoryId, $typeName = null, $typeId = null){
        $sql = "SELECT * FROM types WHERE category_id = ?";
        $params = [$categoryId];
        if ($typeName) {
            $sql .= " AND Tname LIKE ?";
            $params[] = "%$typeName%";
        }
        if ($typeId) {
            $sql .= " AND Tid = ?";
            $params[] = $typeId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryName($id) {
        $stmt = $this->pdo->prepare("SELECT Cname FROM categories WHERE Cid = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function addType($categoryId, $typeName){
        $stmt = $this->pdo->prepare("INSERT INTO types (Tname, category_id) VALUES (:typeName, :categoryId)");
        $stmt->bindParam(':typeName', $typeName, PDO::PARAM_STR);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateProductDetails($id, $data) {
        $sql = "UPDATE products SET Pname = :Pname, Pdescription = :Pdescription, Pimage = :Pimage, price = :price WHERE Pid = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':Pname', $data['Pname'], PDO::PARAM_STR);
        $stmt->bindParam(':Pdescription', $data['Pdescription'], PDO::PARAM_STR);
        $stmt->bindParam(':Pimage', $data['Pimage'], PDO::PARAM_STR);
        $stmt->bindParam(':price', $data['price'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function convertToId_type($type_name){
        $stmt = $this->pdo->prepare("SELECT Tid FROM types WHERE Tname = :name");
        $stmt->bindParam(':name', $type_name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function checkExistSeries($series_name){
        $stmt = $this->pdo->prepare("SELECT seid FROM series WHERE Sname = :name LIMIT 1");
        $stmt->bindParam(':name', $series_name, PDO::PARAM_STR);
        $stmt->execute();
        $seriesId = $stmt->fetchColumn();

        if ($seriesId) {
            return (int)$seriesId;
        }

        $stmt = $this->pdo->prepare("INSERT INTO series (Sname) VALUES (:name)");
        $stmt->bindParam(':name', $series_name, PDO::PARAM_STR);
        $stmt->execute();

        return (int)$this->pdo->lastInsertId();
    }
    public function checkExistManufacturer($manufacturer_name){
        $stmt = $this->pdo->prepare("SELECT Mid FROM manufacturers WHERE Mname = :name LIMIT 1");
        $stmt->bindParam(':name', $manufacturer_name, PDO::PARAM_STR);
        $stmt->execute();
        $manufacturerId = $stmt->fetchColumn();

        if ($manufacturerId) {
            return (int)$manufacturerId;
        }

        $stmt = $this->pdo->prepare("INSERT INTO manufacturers (Mname) VALUES (:name)");
        $stmt->bindParam(':name', $manufacturer_name, PDO::PARAM_STR);
        $stmt->execute();

        return (int)$this->pdo->lastInsertId();
    }
    public function checkExistBrand($brand_name){
        $stmt = $this->pdo->prepare("SELECT Bid FROM brands WHERE Bname = :name LIMIT 1");
        $stmt->bindParam(':name', $brand_name, PDO::PARAM_STR);
        $stmt->execute();
        $brandId = $stmt->fetchColumn();

        if ($brandId) {
            return (int)$brandId;
        }

        $stmt = $this->pdo->prepare("INSERT INTO brands (Bname) VALUES (:name)");
        $stmt->bindParam(':name', $brand_name, PDO::PARAM_STR);
        $stmt->execute();

        return (int)$this->pdo->lastInsertId();
    }

    public function addProduct($productData){
        $Pname = $productData['Pname'];
        $Pdescription = $productData['Pdescription'];
        $price = $productData['price'];
        $type_id = $productData['type_id'];
        $category_id = $productData['category_id'];
        $series_id = $this->checkExistSeries($productData['series']);
        $manufacturer_id = $this->checkExistManufacturer($productData['manufacturer']);
        $brand_id = $this->checkExistBrand($productData['brand']);
        $Pimage = $productData['Pimage'];
        $stmt = $this->pdo->prepare("INSERT INTO products (Pname, Pdescription, Pimage, price, type_id, category_id, series_id, manufacturer_id, brand_id) VALUES (:Pname, :Pdescription, :Pimage, :price, :type_id, :category_id, :series_id, :manufacturer_id, :brand_id)");
        $stmt->bindParam(':Pname', $Pname, PDO::PARAM_STR);
        $stmt->bindParam(':Pdescription', $Pdescription, PDO::PARAM_STR);
        $stmt->bindParam(':Pimage', $Pimage, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':type_id', $type_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':series_id', $series_id, PDO::PARAM_INT);
        $stmt->bindParam(':manufacturer_id', $manufacturer_id, PDO::PARAM_INT);
        $stmt->bindParam(':brand_id', $brand_id, PDO::PARAM_INT);
        $stmt->execute();
        $productId = $this->pdo->lastInsertId();

        return $productId;
    }

    public function convertToCategory($category_id){
        $stmt = $this->pdo->prepare("SELECT Cname FROM categories WHERE Cid = :category_id");
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getProductStockForStore($productId, $storeName) {
        $stmt = $this->pdo->prepare("
            SELECT IN_STOCK.quantity
            FROM IN_STOCK
            JOIN stores ON IN_STOCK.store_id = stores.Sid
            WHERE IN_STOCK.product_id = :productId AND stores.Sname = :storeName
        ");
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':storeName', $storeName, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getCategoryByType($typeId) {
        $stmt = $this->pdo->prepare("
            SELECT categories.Cname, categories.Cid
            FROM categories
            WHERE categories.Cid = (
                SELECT types.category_id
                FROM types
                WHERE types.Tid = :typeId
            )
        ");
        $stmt->bindParam(':typeId', $typeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hideProduct($id){
        $stmt = $this->pdo->prepare("UPDATE products SET is_hidden = 1 WHERE Pid = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function unhideProduct($id){
        $stmt = $this->pdo->prepare("UPDATE products SET is_hidden = 0 WHERE Pid = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}