<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .content-layout {
            display: grid;
            grid-template-columns: 4fr 1fr;
            gap: 20px;
        }

        .sticky-sidebar-desktop {
            position: sticky;
            top: 150px;
            align-self: flex-start;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
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
                <li class="breadcrumb-item active" aria-current="page">Shopping cart</li>
            </ol>
        </nav>
        <div class="container my-3">
            <h2 class="text-center mb-3">Shopping Cart</h2>
            <p class="text-center mb-4">Showing all products in your cart</p>
            <?php if (empty($carts)) : ?>
                <p class="text-center">Your cart is empty.</p>
            <?php else : ?>
            <div class="content-layout">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>    
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col" style="width: 100px">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carts as $item) : ?>
                                <tr>
                                    <td><a href="index.php?route=view-detail&id=<?php echo urlencode($item['product_id']); ?>"><img src="images/products/<?php echo htmlspecialchars($item['Pimage']); ?>" alt="<?php echo htmlspecialchars($item['Pname']); ?>" width="100"></a></td>
                                    <td><a style="text-decoration: none; color: black;" href="index.php?route=view-detail&id=<?php echo urlencode($item['product_id']); ?>"><?php echo htmlspecialchars($item['Pname']); ?></a></td>
                                    <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                    <td>
                                        <input type="number" 
                                                name="quantity" 
                                                class="form-control quantity-input" 
                                                value="<?php echo htmlspecialchars($item['quantity']); ?>"
                                                min="1"
                                                data-id="<?php echo $item['product_id']; ?>">
                                    </td>
                                    <td class="subtotal" data-price="<?php echo $item['price']; ?>">
                                        <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ
                                    </td>
                                    <td>
                                        <form action="index.php?route=view-cart" method="post" style="display: inline;">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['product_id']); ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 1.2rem;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="sticky-sidebar-desktop">
                    <div class="d-flex justify-content-around">
                        <p class="me-3">Total: </p>
                        <h5 class="total"><?php echo number_format($totalPrice, 0, ',', '.'); ?>đ</h5>
                    </div>
                    <form action="index.php?route=view-cart" method="post">
                        <input type="hidden" name="action" value="confirm">
                        <input type="hidden" name="status" value="Placed">
                        <?php if ($_SESSION['city'] != '' && $_SESSION['district'] != '' && $_SESSION['address'] != '' && $_SESSION['phone'] != '') : ?>
                            <button type="submit" class="btn btn-primary w-100 mt-3">Confirm Order</button>
                            <a href="index.php?route=profile-overview" class="btn btn-warning w-100 mt-3">Update Contact Information</a>
                        <?php else: ?>
                            <a href="index.php?route=profile-overview" class="btn btn-warning w-100 mt-3">Please update your contact information before confirming the order</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    include __DIR__ . '/partials/footer.php';
    ?> 
    <script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            let productId = this.getAttribute('data-id');
            let quantity = this.value;

            // Nếu trống hoặc không hợp lệ, set về 1
            if (quantity === '' || quantity < 1 || isNaN(quantity)) {
                quantity = 1;
                this.value = 1;
            }

            fetch("index.php?route=view-cart", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "id=" + productId + "&quantity=" + quantity
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                let price = this.closest('tr').querySelector('.subtotal').getAttribute('data-price');
                let newSubtotal = price * quantity;
                this.closest('tr').querySelector('.subtotal').innerText = 
                    newSubtotal.toLocaleString("vi-VN") + "đ";

                document.querySelector('.total').innerText = data.total.toLocaleString("vi-VN") + "đ";
            });
        });
    });
    </script>
</body>
</html>