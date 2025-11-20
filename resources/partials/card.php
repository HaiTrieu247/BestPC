<style>
    .card-height-fix {
        height: 380px;
    }

    @media (max-width: 768px) {
        .card-height-fix {
            height: auto;
        }
    }
</style>

<div class="col-md-3 mb-4 d-flex">
    <div class="card shadow-sm border-0 card-height-fix">
        <img src="/mywebsite/storage/uploads/<?php echo htmlspecialchars($Pimage); ?>" 
             class="card-img-top" 
             alt="<?php echo htmlspecialchars($Pname); ?>">
        <div class="card-body d-flex flex-column text-center">
            <div class=" mt-auto">
                <h6 class="card-title text-truncate"><?php echo htmlspecialchars($Pname); ?></h6>
                <p class="card-text text-danger fw-bold mb-3"><?php echo number_format($price); ?>đ</p>
                <form action="index.php" method="get" class="mb-0">
                    <input type="hidden" name="route" value="view-detail">
                    <?php if (isset($Ptype)): ?>
                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($Ptype); ?>">
                    <?php endif; ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($Pid); ?>">
                    <?php if (!isset($_SESSION['role']) || $_SESSION['role'] === 'buyer'): ?>
                        <button type="submit" class="btn btn-outline-primary w-100">
                            Buy now
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-outline-primary w-100">
                            Edit Product
                        </button>
                    <?php endif; ?>
                </form>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <form action="index.php?route=hide-product" method="post" class="mt-2">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($Pid); ?>">
                        <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                        <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Are you sure you want to delete this product?');">
                            Hide Product
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
