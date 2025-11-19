<style>
    #mobileSidebarButton {
        position: fixed;
        right: 15px;
        top: 40%;
        transform: translateY(-50%);
        z-index: 1030;
    }
    .sticky-profile-sidebar {
        position: sticky;
        top: 150px;
        align-self: flex-start;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
    }
    
    @media (max-width: 992px) {
    #mobileSidebar {
        width: 280px !important;
    }
}
</style>

<div class="row">
    <div class="d-lg-none">
        <button id="mobileSidebarButton" class="btn btn-outline-secondary w-20" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            <i class="bi bi-person-circle"></i> Profile
        </button>
    </div>

    <div class="d-none d-lg-block sticky-profile-sidebar">
        <div class="list-group">
            <a href="index.php?route=profile-overview" class="list-group-item list-group-item-action <?php echo (!isset($_GET['route']) || $_GET['route'] === 'profile-overview') ? 'active' : ''; ?>">
                <i class="bi bi-person-circle me-2"></i> Profile Overview
            </a>
            <a href="index.php?route=my-orders" class="list-group-item list-group-item-action <?php echo (isset($_GET['route']) && $_GET['route'] === 'my-orders') ? 'active' : ''; ?>">
                <i class="bi bi-bag-check me-2"></i> My Orders
            </a>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileSidebarLabel">Profile Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="list-group">
            <a href="index.php?route=profile-overview" class="list-group-item list-group-item-action <?php echo (!isset($_GET['route']) || $_GET['route'] === 'profile-overview') ? 'active' : ''; ?>">
                <i class="bi bi-person-circle me-2"></i> Profile Overview
            </a>
            <a href="index.php?route=my-orders" class="list-group-item list-group-item-action <?php echo (isset($_GET['route']) && $_GET['route'] === 'my-orders') ? 'active' : ''; ?>">
                <i class="bi bi-bag-check me-2"></i> My Orders
            </a>
        </div>
    </div>
</div>