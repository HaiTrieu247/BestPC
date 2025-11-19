<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
</head>
<body>
    <?php
    include __DIR__ . '/partials/nav-bar.php';
    ?>
    <div class="container">
        <div id="carouselExampleIndicators" class="carousel slide">
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