<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($type); ?></title>
    <style>
        .content-layout {
            display: grid;
            grid-template-columns: 1fr 4fr;
            gap: 20px;
        }

        @media (max-width: 992px) {
            .content-layout {
                display: flex;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php
    include __DIR__ . '/partials/nav-bar.php';
    ?>
    <div class="container">
        <nav class="container py-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none fw-medium" style="color:black" href="index.php?route=home">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($type); ?></li>
            </ol>
        </nav>
        
        <div class="container my-3">
            <h2 class="text-center mb-3"><?php echo htmlspecialchars($type); ?></h2>
            <p class="text-center mb-4">Showing all products for category</p>
            <div class="content-layout">
                <?php
                $route = 'all-products';
                include __DIR__ . '/partials/side-bar.php';
                ?>
                <div>
                    <?php
                    include __DIR__ . '/partials/sorting.php';
                    ?>
                    <div class="row row-gap-4">
                        <?php
                        if (empty($products)) {
                            echo '<p class="text-center">No products found for this category.</p>';
                        } else {
                            foreach ($products as $product) {
                                $Pname = $product['Pname'];
                                $Pimage = $product['Pimage'];
                                $price = $product['price'];
                                $Pid = $product['Pid'];
                                $Ptype = $type;
                                include __DIR__ . '/partials/card.php';
                            }
                        }
                        ?>
                        <nav id="pagination-nav" class="mt-4 mb-5"></nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/mywebsite/public/js/renderPagination.js"></script>
    <script>
        renderPagination(<?= (int)$totalPages ?>, <?= (int)$currentPage ?>);
    </script>
    <?php
    include __DIR__ . '/partials/footer.php';
    ?>
</body>
</html>