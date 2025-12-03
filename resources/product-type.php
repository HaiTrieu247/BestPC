<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View all product types in category: <?php echo htmlspecialchars($categoryName); ?>." />
    <title>Category: <?php echo htmlspecialchars($categoryName); ?></title>
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
                <li class="breadcrumb-item"><a class="text-decoration-none fw-medium" style="color:black" href="index.php?route=product-category">All Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page">Category: <?php echo htmlspecialchars($categoryName); ?></li>
            </ol>
        </nav>
        <div class="d-flex flex-column">
            <div class="d-flex justify-content-between mb-4">
                <form role="search" method="GET" action="index.php">
                    <input type="hidden" name="route" value="product-type">
                    <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($categoryId); ?>">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex gap-2">
                            <div>
                                <p>Search by Name</p>
                                <input class="form-control" type="search" name="type_name" placeholder="Enter name...." aria-label="Search">
                            </div>
                            <div>
                                <p>Search by ID</p>
                                <input class="form-control" type="search" name="type_id" placeholder="Enter ID...." aria-label="Search">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-primary h-50" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                        + Add new Type
                    </button>
                    <a href="index.php?route=product-type&category_id=<?php echo htmlspecialchars($categoryId); ?>" class="btn btn-secondary h-30 text-center">Show All</a>
                </div>
                <div class="modal fade" id="addTypeModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add new Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="index.php?route=product-type" method="POST">
                                    <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($categoryId); ?>">
                                    <input type="hidden" name="action" value="add-type">
                                    <div class="mb-3">
                                        <label>Type Name</label>
                                        <input type="text" name="type_name" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php foreach ($types as $type): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title"><?php echo htmlspecialchars($type['Tname']); ?></h5>
                            <form action="index.php" method="get">
                                <input type="hidden" name="route" value="view-products-by-type">
                                <input type="hidden" name="type_name" value="<?php echo htmlspecialchars($type['Tname']); ?>">
                                <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($categoryId); ?>">
                                <button type="submit" class="btn btn-outline-primary">Edit</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    include __DIR__ . '/partials/footer.php';
    ?>
</body>
</html>