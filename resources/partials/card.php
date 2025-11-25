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
             alt="<?php echo htmlspecialchars($Pname); ?>"
             style="<?php echo ($product['is_hidden']) ? 'opacity: 0.4;' : ''; ?>">
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
                    <div class="d-flex justify-content-between">
                        <?php if (!$product['is_hidden']): ?>
                            <button type="button" class="btn btn-outline-danger w-100 mt-2 hide-product-btn" 
                                    data-product-id="<?php echo htmlspecialchars($Pid); ?>">
                                Hide
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-outline-success w-100 mt-2 unhide-product-btn" 
                                    data-product-id="<?php echo htmlspecialchars($Pid); ?>">
                                Unhide
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!isset($modal_added)): ?>
<?php $modal_added = true; ?>
<!-- Modal thông báo ẩn sản phẩm -->
<div class="modal fade" id="productHiddenModal" tabindex="-1" aria-labelledby="productHiddenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productHiddenModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Successfully hidden!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal thông báo hiện sản phẩm -->
<div class="modal fade" id="productUnhiddenModal" tabindex="-1" aria-labelledby="productUnhiddenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productUnhiddenModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Successfully unhidden!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
if (!window.hideProductHandlerAdded) {
    window.hideProductHandlerAdded = true;
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('hide-product-btn')) {
            if (!confirm('Are you sure you want to hide this product?')) {
                return;
            }
            
            const productId = e.target.dataset.productId;
            const hideBtn = e.target;
            const cardImg = hideBtn.closest('.card').querySelector('img');
            
            fetch('index.php?route=hide-product', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + encodeURIComponent(productId)
            })
            .then(response => response.text())
            .then(() => {
                // Làm mờ ảnh
                cardImg.style.opacity = '0.4';
                
                // Đổi nút Hide thành Unhide
                hideBtn.textContent = 'Unhide';
                hideBtn.classList.remove('btn-outline-danger', 'hide-product-btn');
                hideBtn.classList.add('btn-outline-success', 'unhide-product-btn');
                
                // Hiển thị modal thành công
                const modal = new bootstrap.Modal(document.getElementById('productHiddenModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
        
        // Handler cho unhide product
        if (e.target.classList.contains('unhide-product-btn')) {
            if (!confirm('Are you sure you want to unhide this product?')) {
                return;
            }
            
            const productId = e.target.dataset.productId;
            const unhideBtn = e.target;
            const cardImg = unhideBtn.closest('.card').querySelector('img');
            
            fetch('index.php?route=unhide-product', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + encodeURIComponent(productId)
            })
            .then(response => response.text())
            .then(() => {
                // Bỏ mờ ảnh
                cardImg.style.opacity = '1';
                
                // Đổi nút Unhide thành Hide
                unhideBtn.textContent = 'Hide';
                unhideBtn.classList.remove('btn-outline-success', 'unhide-product-btn');
                unhideBtn.classList.add('btn-outline-danger', 'hide-product-btn');
                
                // Hiển thị modal thành công
                const modal = new bootstrap.Modal(document.getElementById('productUnhiddenModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    });
}
</script>
<?php endif; ?>
