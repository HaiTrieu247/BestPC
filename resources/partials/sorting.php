<style>
    #mobileSortDropdown {
        /* position: fixed;
        right: auto;
        top: 55%;
        transform: translateY(-55%);
        z-index: 1030; */
    }
</style>

<div class="pb-4">
    <div class="d-none d-lg-block">
        <form method="get" action="index.php">
            <?php foreach ($_GET as $key => $value): ?>
                <?php if ($key !== 'sortby'): ?>
                    <?php if (is_array($value)): ?>
                        <?php foreach ($value as $item): ?>
                            <input type="hidden" name="<?php echo htmlspecialchars($key); ?>[]" value="<?php echo htmlspecialchars($item); ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="d-flex gap-4">
                <?php 
                $activeAsc = isset($_GET['sortby']) && $_GET['sortby'] === 'price_asc';
                ?>
                <button 
                    class="btn <?php echo $activeAsc ? 'btn-secondary' : 'btn-outline-secondary'; ?>" 
                    type="submit" 
                    name="sortby" 
                    value="<?php echo $activeAsc ? '' : 'price_asc'; ?>">
                    Price: Low to High
                </button>

                <?php 
                $activeDesc = isset($_GET['sortby']) && $_GET['sortby'] === 'price_desc';
                ?>
                <button 
                    class="btn <?php echo $activeDesc ? 'btn-secondary' : 'btn-outline-secondary'; ?>" 
                    type="submit" 
                    name="sortby" 
                    value="<?php echo $activeDesc ? '' : 'price_desc'; ?>">
                    Price: High to Low
                </button>
                <?php if ($route === 'my-orders' || $route === 'admin-incoming-orders' || $route === 'user-detail'): ?>
                    <?php 
                    $activeAsc = isset($_GET['sortby']) && $_GET['sortby'] === 'date_desc';
                    ?>
                    <button 
                        class="btn <?php echo $activeAsc ? 'btn-secondary' : 'btn-outline-secondary'; ?>" 
                        type="submit" 
                        name="sortby" 
                        value="<?php echo $activeAsc ? '' : 'date_desc'; ?>">
                        Date: Newest
                    </button>

                    <?php 
                    $activeDesc = isset($_GET['sortby']) && $_GET['sortby'] === 'date_asc';
                    ?>
                    <button 
                        class="btn <?php echo $activeDesc ? 'btn-secondary' : 'btn-outline-secondary'; ?>" 
                        type="submit" 
                        name="sortby" 
                        value="<?php echo $activeDesc ? '' : 'date_asc'; ?>">
                        Date: Oldest
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>