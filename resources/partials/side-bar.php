<style>
.accordion-button {
    background-color: #f8f9fa !important;
}

#mobileFilterButton {
    position: fixed;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1030;
}
.sticky-sidebar-desktop {
    position: sticky;
    top: 150px;
    align-self: flex-start;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
}

@media (max-width: 992px) {
    #mobileFilter {
        width: 280px !important;
    }
}
</style>

<div class="row">
    <div class="d-lg-none">
        <button id="mobileFilterButton" class="btn btn-outline-secondary w-20" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileFilter">
            <i class="bi bi-funnel"></i> Filters/Sorting
        </button>
    </div>

    <div class="d-none d-lg-block sticky-sidebar-desktop">
        <form method="get" action="index.php">
            <input type="hidden" name="route" value="<?php echo htmlspecialchars($route); ?>">
            <?php if ($route == "user-detail"): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($userId); ?>">
            <?php endif; ?>
            <?php if ($route == "all-products"): ?>
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
            <?php endif; ?>
            <div class="d-flex justify-content-around">
                <button type="submit" class="btn btn-primary mb-3 w-100">Apply Filters</button>
                <?php if ($route == "all-products"): ?>
                <a href="index.php?route=<?php echo htmlspecialchars($route); ?>&type=<?php echo urlencode($type); ?>" class="btn btn-danger mb-3 w-100">Clear Filters</a>
                <?php else : ?>
                    <a href="index.php?route=<?php echo htmlspecialchars($route); ?>" class="btn btn-danger mb-3 w-100">Clear Filters</a>
                <?php endif; ?>
            </div>
            <div class="accordion" id="filterDesktop">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" style="font-weight: bold" data-bs-toggle="collapse" data-bs-target="#collapsePriceRange">
                            Range of Prices
                        </button>
                    </h2>
                    <div id="collapsePriceRange" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <p>From</p>
                            <input type="number" name="min_price" class="form-control mb-2" placeholder="Min Price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
                            <p>To</p>
                            <input type="number" name="max_price" class="form-control mb-2" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
                        </div>
                    </div>
                </div>
                <?php if ($route === 'my-orders' || $route === 'user-detail'): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" style="font-weight: bold" data-bs-toggle="collapse" data-bs-target="#collapseDateRange">
                                Range of Dates
                            </button>
                        </h2>
                        <div id="collapseDateRange" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p>From</p>
                                <input type="date" name="start_date" class="form-control mb-2" placeholder="Start Date" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                                <p>To</p>
                                <input type="date" name="end_date" class="form-control mb-2" placeholder="End Date" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php foreach ($filter as $filterName => $options): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" style="font-weight: bold" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo htmlspecialchars($filterName); ?>">
                            <?php echo htmlspecialchars($filterName); ?>
                        </button>
                    </h2>
                    <div id="collapse<?php echo htmlspecialchars($filterName); ?>" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <ul class="list-unstyled">
                                <?php foreach ($options as $option): ?>
                                    <li>
                                        <input type="checkbox" 
                                                id="<?php echo htmlspecialchars($option); ?>" 
                                                name="<?php echo htmlspecialchars($filterName); ?>[]" 
                                                value="<?php echo htmlspecialchars($option); ?>" 
                                                <?php if (!empty($_GET[$filterName]) && in_array($option, $_GET[$filterName])) echo 'checked'; ?>>
                                        <label for="<?php echo htmlspecialchars($option); ?>"><?php echo htmlspecialchars($option); ?></label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </form>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileFilter">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Filters/Sorting</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div id="mobileSortDropdown" class="dropdown">
            <button class="btn btn-secondary dropdown-toggle w-100 mb-3" type="button" id="mobileSortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Sort By
            </button>
            <ul class="dropdown-menu w-100" aria-labelledby="mobileSortDropdown">
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
                    <li>
                        <?php 
                        $activeAsc = isset($_GET['sortby']) && $_GET['sortby'] === 'price_asc';
                        ?>
                        <button class="dropdown-item <?php echo $activeAsc ? 'active' : ''; ?>" type="submit" name="sortby" value="price_asc">Price: Low to High</button>
                    </li>
                    <li>
                        <?php 
                        $activeDesc = isset($_GET['sortby']) && $_GET['sortby'] === 'price_desc';
                        ?>
                        <button class="dropdown-item <?php echo $activeDesc ? 'active' : ''; ?>" type="submit" name="sortby" value="price_desc">Price: High to Low</button>
                    </li>
                    <?php if ($route === 'my-orders' || $route === 'user-detail'): ?>
                        <li>
                            <?php 
                            $activeAsc = isset($_GET['sortby']) && $_GET['sortby'] === 'date_desc';
                            ?>
                            <button class="dropdown-item <?php echo $activeAsc ? 'active' : ''; ?>" type="submit" name="sortby" value="date_desc">Date: Newest</button>
                        </li>
                        <li>
                            <?php 
                            $activeDesc = isset($_GET['sortby']) && $_GET['sortby'] === 'date_asc';
                            ?>
                            <button class="dropdown-item <?php echo $activeDesc ? 'active' : ''; ?>" type="submit" name="sortby" value="date_asc">Date: Oldest</button>
                        </li>
                    <?php endif; ?>
                </form>
            </ul>
        </div>
        <div class="accordion" id="filterMobile">
            <form method="get" action="index.php">
                <input type="hidden" name="route" value="<?php echo htmlspecialchars($route); ?>">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                <button type="submit" class="btn btn-primary mb-3 w-100">Apply Filters</button>
                <?php if ($route == "all-products"): ?>
                <a href="index.php?route=<?php echo htmlspecialchars($route); ?>&type=<?php echo urlencode($type); ?>" class="btn btn-danger mb-3 w-100">Clear Filters</a>
                <?php else : ?>
                    <a href="index.php?route=<?php echo htmlspecialchars($route); ?>" class="btn btn-danger mb-3 w-100">Clear Filters</a>
                <?php endif; ?>
                <div class="accordion" id="filterDesktop">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" style="font-weight: bold" data-bs-toggle="collapse" data-bs-target="#collapsePriceRange">
                                Range of Prices
                            </button>
                        </h2>
                        <div id="collapsePriceRange" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p>From</p>
                                <input type="number" name="min_price" class="form-control mb-2" placeholder="Min Price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
                                <p>To</p>
                                <input type="number" name="max_price" class="form-control mb-2" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <?php if ($route === 'my-orders' || $route === 'user-detail'): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" style="font-weight: bold" data-bs-toggle="collapse" data-bs-target="#collapseDateRange">
                                    Range of Dates
                                </button>
                            </h2>
                            <div id="collapseDateRange" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <p>From</p>
                                    <input type="date" name="start_date" class="form-control mb-2" placeholder="Start Date" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                                    <p>To</p>
                                    <input type="date" name="end_date" class="form-control mb-2" placeholder="End Date" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($filter as $filterName => $options): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" style="font-weight: bold" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo htmlspecialchars($filterName); ?>">
                                <?php echo htmlspecialchars($filterName); ?>
                            </button>
                        </h2>
                        <div id="collapse<?php echo htmlspecialchars($filterName); ?>" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ul class="list-unstyled">
                                    <?php foreach ($options as $option): ?>
                                        <li>
                                            <input type="checkbox" 
                                                    id="<?php echo htmlspecialchars($option); ?>" 
                                                    name="<?php echo htmlspecialchars($filterName); ?>[]" 
                                                    value="<?php echo htmlspecialchars($option); ?>" 
                                                    <?php if (!empty($_GET[$filterName]) && in_array($option, $_GET[$filterName])) echo 'checked'; ?>>
                                            <label for="<?php echo htmlspecialchars($option); ?>"><?php echo htmlspecialchars($option); ?></label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>
    </div>
</div>
