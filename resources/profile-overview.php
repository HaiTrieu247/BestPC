<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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