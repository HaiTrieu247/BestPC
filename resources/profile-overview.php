<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage your profile information and account settings." />
    <title>Profile</title>
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
                <li class="breadcrumb-item active" aria-current="page">My profile</li>
            </ol>
        </nav>

        <div class="container my-3">
            <h2 class="text-center mb-3">My profile</h2>
            <p class="text-center mb-4">Profile Overview</p>
            <div class="content-layout">
                <?php
                include __DIR__ . '/partials/user-side-bar.php';
                ?>
                <div class="d-flex flex-column gap-3">
                    <div class="card p-4">
                        <h4 class="mb-3">Profile Information</h4>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                    </div>
                    <div class="card p-4">
                        <h4 class="mb-3"><i class="fa-solid fa-cart-shopping"></i> My Shopping Cart</h4>
                        <a href="index.php?route=view-cart" class="btn btn-info">View My Cart</a>
                    </div>
                    <div class="card p-4">
                        <h4 class="mb-3">Contact Information</h4>
                        <p><strong>City:</strong> <?php echo htmlspecialchars($_SESSION['city']); ?></p>
                        <p><strong>District:</strong> <?php echo htmlspecialchars($_SESSION['district']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($_SESSION['address']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($_SESSION['phone']); ?></p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            Update Contact Information
                        </button>
                        <div class="modal fade" id="editProfileModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Contact Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="index.php?route=profile-overview" method="POST">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">
                                            <div class="mb-3">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control" value="<?= $_POST['city'] ?? ($_SESSION['city'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label>District</label>
                                                <input type="text" name="district" class="form-control" value="<?= $_POST['district'] ?? ($_SESSION['district'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label>Address</label>
                                                <input type="text" name="address" class="form-control" placeholder="Number, Street, etc." value="<?= $_POST['address'] ?? ($_SESSION['address'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label>Phone</label>
                                                <input type="text" name="phone" class="form-control" value="<?= $_POST['phone'] ?? ($_SESSION['phone'] ?? '') ?>">
                                            </div>
                                            <button type="submit" class="btn btn-success">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include __DIR__ . '/partials/footer.php';
    ?>
</body>
</html>