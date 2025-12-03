<header class="border-bottom sticky-top" style="background-color: white">
        <div class="d-none d-lg-block">
            <div class="external">
                <a href="khuyenmai.html"><i class="fa-solid fa-gift"></i> Promotion</a>
                <a href="tragop.html"><i class="fa-solid fa-credit-card"></i> Installment</a>
                <a href="banggia.html"><i class="fa-solid fa-tags"></i> Price list</a>
                <a href="xaydungpc.html"><i class="fa-solid fa-sliders"></i> PC Configuration</a>
                <a href="baohanh.html"><i class="fa-solid fa-shield"></i> Warranty Policy</a>
                <a href="lienhe.html"><i class="fa-solid fa-phone"></i> Contact</a>
            </div>
        </div>
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="index.php"><img src="/mywebsite/public/images/logo.png" alt="Logo" style="width: 100px; height: auto; padding: 0;"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav flex-grow-1 justify-content-between mb-2 mb-lg-0 gap-4">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" style="font-weight: bold; color: black; border: 1px solid #ddd; border-radius: 8px;">
                                    Product Categories
                                </a>
                                <ul class="dropdown-menu">
                                    <?php foreach ($grouped as $category => $subcategories): ?>
                                        <li class="dropdown-submenu">
                                            <a class="dropdown-item dropdown-toggle" href="#">
                                                <?= htmlspecialchars($category) ?>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($subcategories as $subcategory): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="index.php?route=all-products&type=<?= urlencode($subcategory) ?>">
                                                            <?= htmlspecialchars($subcategory) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <li class="nav-item order-3 order-lg-10">
                                <?php
                                include __DIR__ . '/search-bar.php';
                                ?>
                            </li>
                            <li class="nav-item dropdown order-4 order-lg-3">
                                <?php if (isset($_SESSION['id'])): ?>
                                    <a class="nav-link dropdown-toggle user-dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-user"></i>
                                        <span class="username"><?= htmlspecialchars($_SESSION['name']) ?></span>
                                    </a>
                                    <ul class="dropdown-menu" style="padding: 5px;">
                                        <?php if ($_SESSION['role'] == 'admin'): ?>
                                            <li><a class="dropdown-item login-option my-1" href="index.php?route=admin-incoming-orders">Admin Panel</a></li>
                                        <?php else: ?>
                                            <li><a class="dropdown-item login-option my-1" href="index.php?route=profile-overview">Profile</a></li>
                                        <?php endif; ?>
                                        <li><a class="dropdown-item login-option my-1" href="index.php?route=logout">Logout</a></li>
                                    </ul>
                                <?php else: ?>
                                    <a class="nav-link dropdown-toggle" style="font-weight: bold;"href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i> Account
                                    </a>
                                    <ul class="dropdown-menu" style="padding: 5px;">
                                        <li><a class="dropdown-item login-option my-1" href="index.php?route=login">Login</a></li>
                                        <li><a class="dropdown-item login-option my-1" href="index.php?route=register">Create Account</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item social-login google my-1" href="index.php?route=google-login"><i class="fa-brands fa-google me-2"></i>Login with Google</a></li>
                                        <li><a class="dropdown-item social-login facebook my-1" href="index.php?route=facebook-login"><i class="fa-brands fa-facebook me-2"></i>Login with Facebook</a></li>
                                    </ul>
                                <?php endif; ?>
                            </li>
                            <li class="nav-item order-5 order-lg-4 position-relative">
                                <a href="index.php?route=view-cart" class="cart nav-link position-relative">
                                    <i class="fa-solid fa-cart-shopping"></i>Cart
                                    <?php if (isset($_SESSION['cart'])): ?>
                                        <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                            <?= count($_SESSION['cart']) ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <script src="/mywebsite/public/js/bootstrap.bundle.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".dropdown-submenu > .dropdown-toggle").forEach(function (element) {
            element.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();

                let submenu = this.nextElementSibling;
                submenu.classList.toggle("show");

                document.querySelectorAll(".dropdown-submenu .dropdown-menu").forEach(function (menu) {
                    if (menu !== submenu) menu.classList.remove("show");
                });
            });
        });

        document.addEventListener("click", function () {
            document.querySelectorAll(".dropdown-submenu .dropdown-menu").forEach(function (menu) {
                menu.classList.remove("show");
            });
        });
    });
    </script>