<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal">
    Update Product Information
</button>
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Product Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="index.php?route=view-detail" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit-product">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($productId); ?>">
                    <input type="hidden" name="type_id" value="<?php echo htmlspecialchars($product['type_id']); ?>">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="Pname" class="form-control" value="<?= $_POST['Pname'] ?? ($name ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <input type="text" name="Pdescription" class="form-control" value="<?= $_POST['Pdescription'] ?? ($description ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="Pimage" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control" value="<?= $_POST['price'] ?? ($price ?? '') ?>">
                        </div>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>