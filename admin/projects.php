<?php
$admin_page_title = 'Projects';
require_once __DIR__ . '/includes/header.php';

if (isset($_GET['delete'])) { $conn->query("DELETE FROM projects WHERE id = " . intval($_GET['delete'])); setFlash('success', 'Deleted.'); redirect(ADMIN_URL . '/projects.php'); }
if (isset($_GET['toggle'])) { $conn->query("UPDATE projects SET status = IF(status='active','inactive','active') WHERE id = " . intval($_GET['toggle'])); setFlash('success', 'Status updated.'); redirect(ADMIN_URL . '/projects.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $te = sanitize($_POST['title_en']); $ts = sanitize($_POST['title_sw']); $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $te), '-'));
    $de = sanitize($_POST['description_en']); $ds = sanitize($_POST['description_sw']);
    $ce = sanitize($_POST['client_en']); $cs = sanitize($_POST['client_sw']);
    $loc = sanitize($_POST['location']); $cd = sanitize($_POST['completion_date']); $cat = sanitize($_POST['category']);
    $s = sanitize($_POST['status']); $so = intval($_POST['sort_order'] ?? 0); $feat = isset($_POST['is_featured']) ? 1 : 0;
    $image = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) { $fn = 'project_'.time().'_'.basename($_FILES['image']['name']); if (move_uploaded_file($_FILES['image']['tmp_name'], __DIR__.'/../uploads/'.$fn)) $image = $fn; }
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE projects SET title_en=?, title_sw=?, slug=?, description_en=?, description_sw=?, client_en=?, client_sw=?, location=?, completion_date=?, image=?, category=?, status=?, sort_order=?, is_featured=? WHERE id=?");
        $stmt->bind_param("sssssssssssssii", $te, $ts, $slug, $de, $ds, $ce, $cs, $loc, $cd, $image, $cat, $s, $so, $feat, $id); $stmt->execute();
    } else {
        $check = $conn->query("SELECT id FROM projects WHERE slug='$slug'"); if ($check->num_rows > 0) $slug .= '-'.time();
        $stmt = $conn->prepare("INSERT INTO projects (title_en, title_sw, slug, description_en, description_sw, client_en, client_sw, location, completion_date, image, category, status, sort_order, is_featured) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssssssssi", $te, $ts, $slug, $de, $ds, $ce, $cs, $loc, $cd, $image, $cat, $s, $so, $feat); $stmt->execute();
    }
    setFlash('success', 'Saved.'); redirect(ADMIN_URL . '/projects.php');
}

$edit_item = isset($_GET['edit']) ? $conn->query("SELECT * FROM projects WHERE id = ".intval($_GET['edit']))->fetch_assoc() : null;
$items = $conn->query("SELECT * FROM projects ORDER BY sort_order ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4"><h4 class="mb-0"><?php echo $edit_item ? 'Edit Project' : 'Manage Projects'; ?></h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal"><i class="fas fa-plus me-1"></i> Add Project</button></div>

<?php if (!$edit_item): ?>
<div class="card table-card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0"><thead class="table-light"><tr><th>#</th><th>Title</th><th>Client</th><th>Location</th><th>Featured</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody><?php $i=1; while ($item = $items->fetch_assoc()): ?>
        <tr><td><?php echo $i++; ?></td><td><strong><?php echo htmlspecialchars($item['title_en']); ?></strong></td>
        <td><?php echo htmlspecialchars($item['client_en'] ?? 'N/A'); ?></td><td><?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></td>
        <td><span class="badge <?php echo $item['is_featured']?'bg-warning':'bg-light text-dark'; ?>"><?php echo $item['is_featured']?'Yes':'No'; ?></span></td>
        <td><a href="?toggle=<?php echo $item['id']; ?>" class="badge <?php echo $item['status']==='active'?'bg-success':'bg-secondary'; ?> text-decoration-none"><?php echo ucfirst($item['status']); ?></a></td>
        <td><a href="?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-edit"></i></a> <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete"><i class="fas fa-trash"></i></a></td></tr>
    <?php endwhile; ?></tbody></table></div></div></div>
<?php endif; ?>

<div class="modal fade" id="itemModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content">
    <form method="POST" enctype="multipart/form-data">
        <?php if ($edit_item): ?><input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>"><?php endif; ?>
        <input type="hidden" name="existing_image" value="<?php echo $edit_item['image'] ?? ''; ?>">
        <div class="modal-header"><h5 class="modal-title"><?php echo $edit_item ? 'Edit' : 'Add'; ?> Project</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><div class="row g-4">
            <div class="col-md-6"><label class="form-label fw-semibold">Title (English) *</label><input type="text" class="form-control" name="title_en" required value="<?php echo htmlspecialchars($edit_item['title_en'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Title (Kiswahili)</label><input type="text" class="form-control" name="title_sw" value="<?php echo htmlspecialchars($edit_item['title_sw'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Description (English)</label><textarea class="form-control" name="description_en" rows="3"><?php echo htmlspecialchars($edit_item['description_en'] ?? ''); ?></textarea></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Description (Kiswahili)</label><textarea class="form-control" name="description_sw" rows="3"><?php echo htmlspecialchars($edit_item['description_sw'] ?? ''); ?></textarea></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Client (English)</label><input type="text" class="form-control" name="client_en" value="<?php echo htmlspecialchars($edit_item['client_en'] ?? ''); ?>"></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Client (Kiswahili)</label><input type="text" class="form-control" name="client_sw" value="<?php echo htmlspecialchars($edit_item['client_sw'] ?? ''); ?>"></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Location</label><input type="text" class="form-control" name="location" value="<?php echo htmlspecialchars($edit_item['location'] ?? ''); ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Completion Date</label><input type="text" class="form-control" name="completion_date" value="<?php echo htmlspecialchars($edit_item['completion_date'] ?? ''); ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Category</label><input type="text" class="form-control" name="category" value="<?php echo htmlspecialchars($edit_item['category'] ?? ''); ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status"><option value="active" <?php echo ($edit_item['status']??'')==='active'?'selected':''; ?>>Active</option><option value="inactive" <?php echo ($edit_item['status']??'')==='inactive'?'selected':''; ?>>Inactive</option></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Sort</label><input type="number" class="form-control" name="sort_order" value="<?php echo $edit_item['sort_order'] ?? 0; ?>"></div>
            <div class="col-md-6"><div class="form-check form-switch mt-4"><input class="form-check-input" type="checkbox" name="is_featured" value="1" <?php echo ($edit_item['is_featured']??0)?'checked':''; ?>><label class="form-check-label">Featured Project</label></div></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Image</label><input type="file" class="form-control" name="image" accept="image/*"><?php if (!empty($edit_item['image'])): ?><div class="mt-2 p-2 border rounded bg-light d-inline-block"><p class="small text-muted mb-1"><i class="fas fa-image me-1"></i>Current:</p><img src="../uploads/<?php echo htmlspecialchars($edit_item['image']); ?>" style="height:70px; border-radius:4px;"></div><?php endif; ?></div>
        </div></div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form></div></div></div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>