<?php
// Blog form fields partial - used in both add and edit modes
$bp = $edit_post ?? [];
?>
<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Title (English) *</label>
        <input type="text" class="form-control" name="title_en" required value="<?php echo htmlspecialchars($bp['title_en'] ?? ''); ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Title (Kiswahili)</label>
        <input type="text" class="form-control" name="title_sw" value="<?php echo htmlspecialchars($bp['title_sw'] ?? ''); ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Excerpt (English)</label>
        <textarea class="form-control" name="excerpt_en" rows="3"><?php echo htmlspecialchars($bp['excerpt_en'] ?? ''); ?></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Excerpt (Kiswahili)</label>
        <textarea class="form-control" name="excerpt_sw" rows="3"><?php echo htmlspecialchars($bp['excerpt_sw'] ?? ''); ?></textarea>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Content (English) *</label>
        <textarea class="form-control" name="content_en" rows="10" required><?php echo htmlspecialchars($bp['content_en'] ?? ''); ?></textarea>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Content (Kiswahili)</label>
        <textarea class="form-control" name="content_sw" rows="10"><?php echo htmlspecialchars($bp['content_sw'] ?? ''); ?></textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Category</label>
        <input type="text" class="form-control" name="category" value="<?php echo htmlspecialchars($bp['category'] ?? ''); ?>" placeholder="e.g., News, Tips, Updates">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Status</label>
        <select class="form-select" name="status">
            <option value="draft" <?php echo ($bp['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
            <option value="published" <?php echo ($bp['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Featured Image</label>
        <input type="file" class="form-control" name="featured_image" accept="image/*">
        <?php if (!empty($bp['featured_image'])): ?>
            <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                <p class="small text-muted mb-1"><i class="fas fa-image me-1"></i>Current Image:</p>
                <img src="../uploads/<?php echo htmlspecialchars($bp['featured_image']); ?>" alt="Current image" style="height: 80px; max-width: 200px; object-fit: cover; border-radius: 4px;">
                <br><small class="text-muted"><?php echo htmlspecialchars($bp['featured_image']); ?></small>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-1"></i> Save Post</button>
    </div>
</div>

<script>
function resetForm() {
    document.querySelector('#blogModal form').reset();
}
</script>