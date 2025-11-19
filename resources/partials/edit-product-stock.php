<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal">
    Update Product Stock
</button>
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Product Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="index.php?route=change-product-stock" method="POST">
                    <input type="hidden" name="action" value="edit-product">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($productId); ?>">
                    <input type="hidden" name="store_name" value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>">
                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" name="quantity" class="form-control">
                        </div>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>