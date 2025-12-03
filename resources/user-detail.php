<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View detailed information for user ID <?php echo htmlspecialchars($userId); ?>." />
    <title>User Detail: ID = <?php echo htmlspecialchars($userId); ?></title>
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
<body class="d-flex flex-column min-vh-100">
    <?php
    include __DIR__ . '/partials/nav-bar.php';
    ?>

    <div class="container flex-fill">
        <nav class="container py-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none fw-medium" style="color:black" href="index.php?route=users-management">All users</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Detail: ID = <?php echo htmlspecialchars($userId); ?></li>
            </ol>
        </nav>
            <div class="d-flex gap-3 justify-content-center">
                <div>
                    <h4 class="mb-3">Profile Information</h4>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div>
                    <h4 class="mb-3">Contact Information</h4>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
                    <p><strong>District:</strong> <?php echo htmlspecialchars($user['district']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                </div>
            </div>
            <h4 class="text-center mt-4 mb-3">Order History</h4>
            <div class="content-layout">
                <?php
                $route = 'user-detail';
                include __DIR__ . '/partials/side-bar.php';
                ?> 
                <div class="d-flex flex-column">
                    <?php
                    include __DIR__ . '/partials/sorting.php';
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 120px">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Date Ordered</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($orders as $order): ?>
                                    <tr>
                                        <td><a href="index.php?route=view-detail&id=<?php echo urlencode($order['product_id']); ?>"><img src="/mywebsite/storage/uploads/<?php echo htmlspecialchars($order['Pimage']); ?>" alt="<?php echo htmlspecialchars($order['Pname']); ?>" width="100"></a></td>
                                        <td><a style="text-decoration: none; color: black;" href="index.php?route=view-detail&id=<?php echo urlencode($order['product_id']); ?>"><?php echo htmlspecialchars($order['Pname']); ?></a></td>
                                        <td><?php echo number_format($order['product_price'], 0, ',', '.'); ?>đ</td>
                                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                        <td><?php echo number_format($order['subtotal'], 0, ',', '.'); ?>đ</td>
                                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                        <td>
                                            <?php 
                                                echo htmlspecialchars($order['city']) . ', ' . 
                                                     htmlspecialchars($order['district']) . ', ' . 
                                                     htmlspecialchars($order['address']); 
                                            ?>
                                            <p class="fw-bold"><?php echo htmlspecialchars($order['phone']); ?></p>
                                        </td>
                                        <td>
                                            <p><?php echo htmlspecialchars($order['status']); ?></p>
                                            <form action="index.php?route=user-detail" method="post">
                                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($order['user_id']); ?>">
                                                <?php if ($order['status'] === 'Placed'): ?>
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                                    <button type="submit" name="action" value="cancel" class="btn btn-danger btn-sm mt-2">Cancel</button>
                                                    <button type="submit" name="action" value="ship" class="btn btn-primary btn-sm mt-2">Ship</button>
                                                <?php elseif ($order['status'] === 'Shipped'): ?>
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                                    <button type="submit" name="action" value="delivered" class="btn btn-success btn-sm mt-2">Delivered</button>
                                                <?php elseif ($order['status'] === 'Cancel Awaiting'): ?>
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                                    <button type="submit" name="action" value="cancel" class="btn btn-warning btn-sm mt-2">Approve</button>
                                                <?php endif; ?>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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