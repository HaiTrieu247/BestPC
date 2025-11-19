<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php
    include __DIR__ . '/partials/nav-bar.php';
    ?>

    <div class="container my-5">
        <?php if(isset($error_login)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_login); ?>
            </div>
        <?php endif; ?>
        <h2>Login</h2>
        <form action="index.php?route=login" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <?php
    include __DIR__ . '/partials/footer.php';
    ?>

</body>
</html>