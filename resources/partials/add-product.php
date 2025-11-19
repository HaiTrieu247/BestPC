<button type="button" class="btn btn-primary h-50" data-bs-toggle="modal" data-bs-target="#addProductModal">
    + Add Product
</button>
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="index.php?route=view-products-by-type" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add-product">
                    <input type="hidden" name="type_id" value="<?php echo htmlspecialchars($typeId); ?>">
                    <input type="hidden" name="type_name" value="<?php echo htmlspecialchars($type); ?>">
                    <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($categoryId); ?>">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="Pname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" name="Pdescription" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="Pimage" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="text" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Series</label>
                        <input type="text" name="series" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Manufacturer</label>
                        <input type="text" name="manufacturer" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Brand</label>
                        <input type="text" name="brand" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>