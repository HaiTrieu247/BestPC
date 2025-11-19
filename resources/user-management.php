<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
        <div class="container my-3">
            <h2 class="text-center mb-3">Admin Panel</h2>
            <p class="text-center mb-4">Incoming Orders</p>
            <div class="content-layout">
                <?php
                include __DIR__ . '/partials/admin-side-bar.php';
                ?>
                <div class="d-flex flex-column">
                    <div class="d-flex gap-4 mb-4">
                        <form role="search" method="GET" action="index.php">
                            <input type="hidden" name="route" value="users-management">
                            <div class="d-flex gap-2">
                                <div>
                                    <p>Search by Name</p>
                                    <input class="form-control" type="search" name="searchByName" placeholder="Enter name...." aria-label="Search">
                                </div>
                                <div>
                                    <p>Search by ID</p>
                                    <input class="form-control" type="search" name="searchById" placeholder="Enter ID...." aria-label="Search">
                                </div>
                            </div>
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                    <?php foreach ($users as $user): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title">User ID: <?php echo htmlspecialchars($user['id']); ?></h5>
                                    <form action="index.php" method="get">
                                        <input type="hidden" name="route" value="user-detail">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                        <button type="submit" class="btn btn-primary">View Details</button>
                                    </form>
                                </div>
                                <p class="card-text"><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                                <p class="card-text"><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    include __DIR__ . '/partials/footer.php';
    ?>
    
</body>
</html>