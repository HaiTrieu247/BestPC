<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create a new account to start shopping with exclusive deals." />
    <title>Register</title>
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

    <div class="container my-5">
        <?php if(isset($error_register)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_register); ?>
            </div>
        <?php endif; ?>
        <h2>Register</h2>
        <form action="index.php?route=register" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name"
                        value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>"
                        required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                        value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                        required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirmed_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmed_password" name="confirmed_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <?php
    include __DIR__ . '/partials/footer.php';
    ?>

</body>
</html>