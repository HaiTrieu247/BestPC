<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($name); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php
    include __DIR__ . '/partials/nav-bar.php';
    ?>
    <div class="container flex-fill">
        <nav class="container py-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none fw-medium" style="color:black" href="index.php?route=home">Home</a></li>
                <li class="breadcrumb-item"><a class="text-decoration-none fw-medium" style="color:black" href="index.php?route=all-products&type=<?php echo urlencode($type); ?>"><?php echo htmlspecialchars($type); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($name); ?></li>
            </ol>
        </nav>
        <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            include __DIR__ .'/partials/edit-product-detail.php';
            echo'
                <a class="btn btn-warning" href="index.php?route=view-products-by-type&type_name='.urlencode($type).'&category_id='.urlencode($categoryId).'">Go back</a>
            ';
        }
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'store') {
            include __DIR__ .'/partials/edit-product-stock.php';
        }
        ?>
        <div class="container">
            <div class="d-grid gap-4" style="grid-template-columns: 1fr 2fr;">
                <div>
                    <img src="/mywebsite/public/images/products/<?php echo htmlspecialchars($image); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>" class="img-fluid">
                </div>
                <div>
                    <h1><?php echo htmlspecialchars($name); ?></h1>
                    <p class="price" style="font-size: 24px; color: red; font-weight: bold;"><?php echo number_format($price, 0, ',', '.'); ?> VND</p>
                    <p class="description"><?php echo nl2br(htmlspecialchars($description)); ?></p>
                    <h4>Available Stores:</h4>
                    <ul style="list-style-type: none; padding: 0;">
                        <?php
                        if (!empty($stores)) {
                            $has_stock = false;
                            foreach ($stores as $store) {
                                if ($store["quantity"] > 0) {
                                    $has_stock = true;
                                    echo '<li><a href="'. htmlspecialchars($store["Smap_url"]) .'" class="text-decoration-none fw-semibold" style="color:rgba(8, 65, 179, 1);">' . htmlspecialchars($store["Sname"]) . ' - ' . htmlspecialchars($store["Slocation"]) . ' (' . $store["quantity"] . '  products available)</a></li>';
                                }
                            }
                            if ($has_stock) {
                                if (isset($_SESSION['id'])) {
                                    echo'
                                    <form action="index.php?route=view-detail" method="post" class="mb-0 mt-3">
                                        <input type="hidden" name="id" value=" '.htmlspecialchars($id). '">
                                        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#cartModal">
                                            <i class="bi bi-cart-fill me-2"></i>Add to cart
                                        </button>
                                        <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true"> 
                                            <div class="modal-dialog"> 
                                                <div class="modal-content"> 
                                                    <div class="modal-header"> 
                                                        <h5 class="modal-title" id="cartModalLabel">Added to Cart</h5> 
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Please confirm your order.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                                        </div>                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    ';
                                } else {
                                    echo '
                                    <p class="py-4">You have to log in before buying this product</p>';
                                }
                            } else {
                                echo "<li class='text-muted'>This product is out of stock.</li>";
                            }
                        } else {
                            echo "<li class='text-muted'>No stores have this product available.</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
    include __DIR__ . '/partials/footer.php';
    ?>
</script>
</body>
</html>