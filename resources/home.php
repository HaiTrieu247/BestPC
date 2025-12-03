<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find the best deals on computers, gaming laptops, PC parts, and tech accessories. Wide selection, competitive prices, and fast delivery." />
    <title>Home Page</title>
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
    </style>
</head>
<body>
    <?php
    include __DIR__ . '/partials/nav-bar.php';
    ?>
    <div class="container">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner overflow-hidden rounded-4">
                <div class="carousel-item active">
                    <img src="images/thumbnail/thumbnail_3.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="images/thumbnail/thumbnail_2.png" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <div class="container my-5">
        <h2 class="text-center mb-4">Popular Products</h2>

        <?php foreach ($types as $type): ?>
            <div class="container my-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold text-primary mb-0"><?php echo htmlspecialchars($type['Tname']); ?></h3>

                    <form action="index.php" method="get" class="mb-0">
                        <input type="hidden" name="route" value="all-products">
                        <input type="hidden" name="type" value="<?php echo htmlspecialchars($type['Tname']); ?>">
                        <button type="submit" class="btn btn-link text-danger fw-semibold p-0">
                            View all products &gt;
                        </button>
                    </form>
                </div>

                <div class="row g-3">
                    <?php if (!empty($feature[$type['Tname']])): ?>
                        <?php foreach ($feature[$type['Tname']] as $product): ?>
                            <?php
                                $Pname = $product['Pname'];
                                $Pimage = $product['Pimage'];
                                $price = $product['price'];
                                $Pid = $product['Pid'];
                                $Ptype = $type['Tname'];

                                include __DIR__ . '/partials/card.php';
                            ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted fst-italic">No featured products available.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    include __DIR__ . '/partials/footer.php';
    ?>
</body>
</html>