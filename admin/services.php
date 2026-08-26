<?php
$admin_page_title = 'Services';
require_once __DIR__ . '/includes/header.php';

if (isset($_GET['delete'])) { $conn->query("DELETE FROM services WHERE id = " . intval($_GET['delete'])); setFlash('success', 'Deleted.'); redirect(ADMIN_URL . '/services.php'); }
if (isset($_GET['toggle'])) { $conn->query("UPDATE services SET status = IF(status='active','inactive','active') WHERE id = " . intval($_GET['toggle'])); setFlash('success', 'Status updated.'); redirect(ADMIN_URL . '/services.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $te = sanitize($_POST['title_en']); $ts = sanitize($_POST['title_sw']); $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $te), '-'));
    $de = sanitize($_POST['description_en']); $ds = sanitize($_POST['description_sw']); $icon = sanitize($_POST['icon'] ?? 'fas fa-cog'); $s = sanitize($_POST['status']); $so = intval($_POST['sort_order'] ?? 0);
    $image = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) { $fn = 'service_'.time().'_'.basename($_FILES['image']['name']); if (move_uploaded_file($_FILES['image']['tmp_name'], __DIR__.'/../uploads/'.$fn)) $image = $fn; }
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE services SET title_en=?, title_sw=?, slug=?, description_en=?, description_sw=?, icon=?, image=?, status=?, sort_order=? WHERE id=?");
        $stmt->bind_param("ssssssssii", $te, $ts, $slug, $de, $ds, $icon, $image, $s, $so, $id); $stmt->execute();
    } else {
        $check = $conn->query("SELECT id FROM services WHERE slug='$slug'"); if ($check->num_rows > 0) $slug .= '-'.time();
        $stmt = $conn->prepare("INSERT INTO services (title_en, title_sw, slug, description_en, description_sw, icon, image, status, sort_order) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssi", $te, $ts, $slug, $de, $ds, $icon, $image, $s, $so); $stmt->execute();
    }
    setFlash('success', 'Saved.'); redirect(ADMIN_URL . '/services.php');
}

$edit_item = isset($_GET['edit']) ? $conn->query("SELECT * FROM services WHERE id = ".intval($_GET['edit']))->fetch_assoc() : null;
$items = $conn->query("SELECT * FROM services ORDER BY sort_order ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4"><h4 class="mb-0"><?php echo $edit_item ? 'Edit Service' : 'Manage Services'; ?></h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal"><i class="fas fa-plus me-1"></i> Add Service</button></div>

<?php if (!$edit_item): ?>
<div class="card table-card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0"><thead class="table-light"><tr><th>#</th><th>Title</th><th>Icon</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
    <tbody><?php $i=1; while ($item = $items->fetch_assoc()): ?>
        <tr><td><?php echo $i++; ?></td><td><strong><?php echo htmlspecialchars($item['title_en']); ?></strong></td>
        <td><i class="<?php echo htmlspecialchars($item['icon']); ?>"></i></td>
        <td><a href="?toggle=<?php echo $item['id']; ?>" class="badge <?php echo $item['status']==='active'?'bg-success':'bg-secondary'; ?> text-decoration-none"><?php echo ucfirst($item['status']); ?></a></td>
        <td><?php echo $item['sort_order']; ?></td>
        <td><a href="?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-edit"></i></a> <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete"><i class="fas fa-trash"></i></a></td></tr>
    <?php endwhile; ?></tbody></table></div></div></div>
<?php endif; ?>

<div class="modal fade" id="itemModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form method="POST" enctype="multipart/form-data">
        <?php if ($edit_item): ?><input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>"><?php endif; ?>
        <input type="hidden" name="existing_image" value="<?php echo $edit_item['image'] ?? ''; ?>">
        <div class="modal-header"><h5 class="modal-title"><?php echo $edit_item ? 'Edit' : 'Add'; ?> Service</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><div class="row g-4">
            <div class="col-md-6"><label class="form-label fw-semibold">Title (English) *</label><input type="text" class="form-control" name="title_en" required value="<?php echo htmlspecialchars($edit_item['title_en'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Title (Kiswahili)</label><input type="text" class="form-control" name="title_sw" value="<?php echo htmlspecialchars($edit_item['title_sw'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Description (English)</label><textarea class="form-control" name="description_en" rows="4"><?php echo htmlspecialchars($edit_item['description_en'] ?? ''); ?></textarea></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Description (Kiswahili)</label><textarea class="form-control" name="description_sw" rows="4"><?php echo htmlspecialchars($edit_item['description_sw'] ?? ''); ?></textarea></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Icon (FA class)</label><input type="text" class="form-control" name="icon" value="<?php echo htmlspecialchars($edit_item['icon'] ?? 'fas fa-cog'); ?>" placeholder="fas fa-balance-scale"><small class="form-text text-muted">Default: fas fa-cog</small></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status"><option value="active" <?php echo ($edit_item['status']??'')==='active'?'selected':''; ?>>Active</option><option value="inactive" <?php echo ($edit_item['status']??'')==='inactive'?'selected':''; ?>>Inactive</option></select></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Sort Order</label><input type="number" class="form-control" name="sort_order" value="<?php echo $edit_item['sort_order'] ?? 0; ?>"></div>
            <div class="col-12"><label class="form-label fw-semibold">Image</label><input type="file" class="form-control" name="image" accept="image/*"><?php if (!empty($edit_item['image'])): ?><div class="mt-2 p-2 border rounded bg-light d-inline-block"><p class="small text-muted mb-1"><i class="fas fa-image me-1"></i>Current:</p><img src="../uploads/<?php echo htmlspecialchars($edit_item['image']); ?>" style="height:70px; border-radius:4px;"></div><?php endif; ?></div>
        </div></div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form></div></div></div>

<?php if ($edit_item): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('itemModal'));
    modal.show();
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>