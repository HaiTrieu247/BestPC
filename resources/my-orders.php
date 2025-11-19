<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
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
<body class="d-flex flex-column min-vh-100">
    <?php
    include __DIR__ . '/partials/nav-bar.php';
    ?>

    <div class="container flex-fill">
        <nav class="container py-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none fw-medium" style="color:black" href="index.php?route=home">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My profile</li>
            </ol>
        </nav>     
        <div class="container my-3">
            <h2 class="text-center mb-3">My profile</h2>
            <p class="text-center mb-4">All orders</p>
            <div class="content-layout">
                <div class="d-flex flex-column gap-3">
                <?php
                include __DIR__ . '/partials/user-side-bar.php';
                $route = 'my-orders';
                include __DIR__ . '/partials/side-bar.php';
                ?> 
                </div>
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
                                            <form action="index.php?route=my-orders" method="post">
                                                <?php if ($order['status'] === 'Placed'): ?>
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                                    <button type="submit" name="action" value="cancel" class="btn btn-danger btn-sm mt-2">Cancel</button>
                                                <?php else: ?>
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                                    <button type="submit" name="action" value="placed" class="btn btn-primary btn-sm mt-2">Re-order</button>
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