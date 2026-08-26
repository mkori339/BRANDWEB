<?php
$admin_page_title = 'Testimonials';
require_once __DIR__ . '/includes/header.php';

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM testimonials WHERE id = " . intval($_GET['delete']));
    setFlash('success', 'Deleted.');
    redirect(ADMIN_URL . '/testimonials.php');
}
if (isset($_GET['toggle'])) {
    $conn->query("UPDATE testimonials SET status = IF(status='active','inactive','active') WHERE id = " . intval($_GET['toggle']));
    setFlash('success', 'Status updated.');
    redirect(ADMIN_URL . '/testimonials.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $cn = sanitize($_POST['client_name']);
    $ct = sanitize($_POST['client_title']);
    $co = sanitize($_POST['company']);
    $ce = sanitize($_POST['content_en']);
    $cs = sanitize($_POST['content_sw']);
    $r = intval($_POST['rating']);
    $s = sanitize($_POST['status']);
    $so = intval($_POST['sort_order'] ?? 0);
    $photo = $_POST['existing_photo'] ?? '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $fn = 'testimonial_' . time() . '_' . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/../uploads/' . $fn))
            $photo = $fn;
    }

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE testimonials SET client_name=?, client_title=?, company=?, content_en=?, content_sw=?, rating=?, photo=?, status=?, sort_order=? WHERE id=?");
        $stmt->bind_param("sssssissii", $cn, $ct, $co, $ce, $cs, $r, $photo, $s, $so, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO testimonials (client_name, client_title, company, content_en, content_sw, rating, photo, status, sort_order) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssissi", $cn, $ct, $co, $ce, $cs, $r, $photo, $s, $so);
        $stmt->execute();
    }
    setFlash('success', 'Saved.');
    redirect(ADMIN_URL . '/testimonials.php');
}

$items = $conn->query("SELECT * FROM testimonials ORDER BY sort_order ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Manage Testimonials</h4>
    <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-plus me-1"></i> Add Testimonial</button>
</div>

<div class="card table-card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>#</th><th>Name</th><th>Title/Company</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody><?php $i = 1; while ($item = $items->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($item['client_name']); ?></strong></td>
                <td><?php echo htmlspecialchars($item['client_title'] . ($item['company'] ? ' - ' . $item['company'] : '')); ?></td>
                <td><?php for ($s = 0; $s < $item['rating']; $s++) echo '<i class="fas fa-star text-warning"></i>'; ?></td>
                <td><a href="?toggle=<?php echo $item['id']; ?>" class="badge <?php echo $item['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?> text-decoration-none"><?php echo ucfirst($item['status']); ?></a></td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-action" onclick="openEditModal(this)" data-id="<?php echo $item['id']; ?>" data-client_name="<?php echo htmlspecialchars($item['client_name']); ?>" data-client_title="<?php echo htmlspecialchars($item['client_title']); ?>" data-company="<?php echo htmlspecialchars($item['company']); ?>" data-content_en="<?php echo htmlspecialchars($item['content_en']); ?>" data-content_sw="<?php echo htmlspecialchars($item['content_sw']); ?>" data-rating="<?php echo $item['rating']; ?>" data-status="<?php echo $item['status']; ?>" data-sort_order="<?php echo $item['sort_order']; ?>" data-photo="<?php echo htmlspecialchars($item['photo'] ?? ''); ?>"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-action btn-delete-item" data-url="?delete=<?php echo $item['id']; ?>"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        <?php endwhile; ?></tbody>
    </table></div></div></div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="itemModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form method="POST" enctype="multipart/form-data" id="testimonialForm">
        <input type="hidden" name="id" id="form_id" value="">
        <input type="hidden" name="existing_photo" id="form_existing_photo" value="">
        <div class="modal-header"><h5 class="modal-title" id="modalTitle">Add Testimonial</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><div class="row g-4">
            <div class="col-md-4"><label class="form-label fw-semibold">Client Name *</label><input type="text" class="form-control" name="client_name" id="form_client_name" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Title/Position</label><input type="text" class="form-control" name="client_title" id="form_client_title"></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Company</label><input type="text" class="form-control" name="company" id="form_company"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Content (English) *</label><textarea class="form-control" name="content_en" id="form_content_en" rows="4" required></textarea></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Content (Kiswahili)</label><textarea class="form-control" name="content_sw" id="form_content_sw" rows="4"></textarea></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Rating (1-5)</label><select class="form-select" name="rating" id="form_rating"><?php for ($r = 5; $r >= 1; $r--): ?><option value="<?php echo $r; ?>"><?php echo $r; ?> Stars</option><?php endfor; ?></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" id="form_status"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Sort Order</label><input type="number" class="form-control" name="sort_order" id="form_sort_order" value="0"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Photo</label><input type="file" class="form-control" name="photo" accept="image/*" id="form_photo"><div id="form_photo_preview" class="mt-2" style="display:none;"><p class="small text-muted mb-1"><i class="fas fa-image me-1"></i>Current:</p><img id="form_photo_img" src="" style="height: 70px; width: 70px; border-radius: 50%; object-fit: cover;"></div></div>
        </div></div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
</div></div></div>

<script>
var itemModal;
document.addEventListener('DOMContentLoaded', function() {
    itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
});

function openAddModal() {
    document.getElementById('testimonialForm').reset();
    document.getElementById('form_id').value = '';
    document.getElementById('form_existing_photo').value = '';
    document.getElementById('modalTitle').textContent = 'Add Testimonial';
    document.getElementById('form_sort_order').value = '0';
    document.getElementById('form_status').value = 'active';
    document.getElementById('form_rating').value = '5';
    var pp = document.getElementById('form_photo_preview');
    pp.style.display = 'none';
    itemModal.show();
}

function openEditModal(btn) {
    var d = btn.dataset;
    document.getElementById('testimonialForm').reset();
    document.getElementById('form_id').value = d.id;
    document.getElementById('form_existing_photo').value = d.photo;
    document.getElementById('form_client_name').value = d.client_name;
    document.getElementById('form_client_title').value = d.client_title;
    document.getElementById('form_company').value = d.company;
    document.getElementById('form_content_en').value = d.content_en;
    document.getElementById('form_content_sw').value = d.content_sw;
    document.getElementById('form_rating').value = d.rating;
    document.getElementById('form_status').value = d.status;
    document.getElementById('form_sort_order').value = d.sort_order;
    document.getElementById('modalTitle').textContent = 'Edit Testimonial';
    var pp = document.getElementById('form_photo_preview');
    if (d.photo) {
        pp.style.display = 'block';
        document.getElementById('form_photo_img').src = '../uploads/' + d.photo;
    } else {
        pp.style.display = 'none';
    }
    itemModal.show();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
