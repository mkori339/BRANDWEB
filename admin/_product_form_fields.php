<?php
$ip = $edit_item ?? [];
?>
<div class="row g-4">
    <div class="col-md-6"><label class="form-label fw-semibold">Name (English) *</label><input type="text" class="form-control" name="name_en" required value="<?php echo htmlspecialchars($ip['name_en'] ?? ''); ?>"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Name (Kiswahili)</label><input type="text" class="form-control" name="name_sw" value="<?php echo htmlspecialchars($ip['name_sw'] ?? ''); ?>"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Description (English)</label><textarea class="form-control" name="description_en" rows="4"><?php echo htmlspecialchars($ip['description_en'] ?? ''); ?></textarea></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Description (Kiswahili)</label><textarea class="form-control" name="description_sw" rows="4"><?php echo htmlspecialchars($ip['description_sw'] ?? ''); ?></textarea></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Features (English, one per line)</label><textarea class="form-control" name="features_en" rows="4"><?php echo htmlspecialchars($ip['features_en'] ?? ''); ?></textarea></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Features (Kiswahili, one per line)</label><textarea class="form-control" name="features_sw" rows="4"><?php echo htmlspecialchars($ip['features_sw'] ?? ''); ?></textarea></div>
    <div class="col-md-3"><label class="form-label fw-semibold">Category</label><input type="text" class="form-control" name="category" value="<?php echo htmlspecialchars($ip['category'] ?? ''); ?>" placeholder="e.g., Digital, Platform, Kitchen"></div>
    <div class="col-md-3"><label class="form-label fw-semibold">Price</label><input type="text" class="form-control" name="price" value="<?php echo htmlspecialchars($ip['price'] ?? ''); ?>" placeholder="e.g., From TSh 500,000"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Sort Order</label><input type="number" class="form-control" name="sort_order" value="<?php echo $ip['sort_order'] ?? 0; ?>"></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status"><option value="active" <?php echo ($ip['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option><option value="inactive" <?php echo ($ip['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option></select></div>
    <div class="col-md-2"><label class="form-label fw-semibold">Featured</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="is_featured" value="1" <?php echo ($ip['is_featured'] ?? 0) ? 'checked' : ''; ?>><label class="form-check-label">Featured</label></div></div>
    <div class="col-12"><label class="form-label fw-semibold">Image</label><input type="file" class="form-control" name="image" accept="image/*"><?php if (!empty($ip['image'])): ?>
                                            <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                                <p class="small text-muted mb-1"><i class="fas fa-image me-1"></i>Current Image:</p>
                                                <img src="../uploads/<?php echo htmlspecialchars($ip['image']); ?>" alt="Current image" style="height: 80px; max-width: 200px; object-fit: cover; border-radius: 4px;">
                                                <br><small class="text-muted"><?php echo htmlspecialchars($ip['image']); ?></small>
                                            </div>
                                        <?php endif; ?></div>
    <div class="col-12"><button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-1"></i> Save Product</button></div>
</div>