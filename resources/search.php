<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Search results for '<?php echo htmlspecialchars($query); ?>' - Find the best products." />
    <title>Search results for '<?php echo htmlspecialchars($query); ?>'</title>
    <link rel="shortcut icon" href="/mywebsite/public/images/logo.png" type="image/png">
    <link rel="stylesheet" href="/mywebsite/public/css/bootstrap.css">
    <link rel="stylesheet" href="/mywebsite/public/css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu > .dropdown-menu {
            top: 0;
            left: 100%;
            margin-left: -1px;
            margin-top: -1px;
        }

        .nav-link.user-dropdown {
            max-width: 120px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: bold;
            white-space: nowrap;
        }

        .nav-link.user-dropdown .username {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 110px;
        }

        .nav-link.user-dropdown::after {
            margin-left: auto;
        }

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
                <li class="breadcrumb-item active" aria-current="page">Search results for '<?php echo htmlspecialchars($query); ?>'</li>
            </ol>
        </nav>
        
        <div class="container my-3">
            <h2 class="text-center mb-3">Search results for '<?php echo htmlspecialchars($query); ?>'</h2>
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
                        include __DIR__ . '/partials/card.php';
                    }
                }
                ?>
                <nav id="pagination-nav" class="mt-4 mb-5"></nav>
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