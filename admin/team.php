<?php
$admin_page_title = 'Team Members';
require_once __DIR__ . '/includes/header.php';

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM team_members WHERE id = " . intval($_GET['delete']));
    setFlash('success', 'Team member deleted.');
    redirect(ADMIN_URL . '/team.php');
}
if (isset($_GET['toggle'])) {
    $conn->query("UPDATE team_members SET status = IF(status='active','inactive','active') WHERE id = " . intval($_GET['toggle']));
    setFlash('success', 'Status updated.');
    redirect(ADMIN_URL . '/team.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $ne = sanitize($_POST['name_en']);
    $ns = sanitize($_POST['name_sw'] ?? '');
    $pe = sanitize($_POST['position_en']);
    $ps = sanitize($_POST['position_sw'] ?? '');
    $de = sanitize($_POST['department_en'] ?? '');
    $ds = sanitize($_POST['department_sw'] ?? '');
    $fb = sanitize($_POST['facebook_url'] ?? '');
    $li = sanitize($_POST['linkedin_url'] ?? '');
    $tw = sanitize($_POST['twitter_url'] ?? '');
    $em = sanitize($_POST['email'] ?? '');
    $s = sanitize($_POST['status']);
    $so = intval($_POST['sort_order'] ?? 0);
    $photo = $_POST['existing_photo'] ?? '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $fn = 'team_' . time() . '_' . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/../uploads/' . $fn))
            $photo = $fn;
    }

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE team_members SET name_en=?, name_sw=?, position_en=?, position_sw=?, department_en=?, department_sw=?, photo=?, facebook_url=?, linkedin_url=?, twitter_url=?, email=?, status=?, sort_order=? WHERE id=?");
        $stmt->bind_param("ssssssssssssii", $ne, $ns, $pe, $ps, $de, $ds, $photo, $fb, $li, $tw, $em, $s, $so, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO team_members (name_en, name_sw, position_en, position_sw, department_en, department_sw, photo, facebook_url, linkedin_url, twitter_url, email, status, sort_order) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssssssi", $ne, $ns, $pe, $ps, $de, $ds, $photo, $fb, $li, $tw, $em, $s, $so);
        $stmt->execute();
    }
    setFlash('success', 'Team member saved.');
    redirect(ADMIN_URL . '/team.php');
}

$items = $conn->query("SELECT * FROM team_members ORDER BY sort_order ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Manage Team Members</h4>
    <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-plus me-1"></i> Add Member</button>
</div>

<div class="card table-card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>#</th><th>Photo</th><th>Name</th><th>Position</th><th>Department</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
        <tbody><?php $i = 1; while ($item = $items->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td>
                    <?php if ($item['photo'] && file_exists(__DIR__ . '/../uploads/' . $item['photo'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($item['photo']); ?>" style="height: 45px; width: 45px; border-radius: 50%; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="height: 45px; width: 45px;"><i class="fas fa-user"></i></div>
                    <?php endif; ?>
                </td>
                <td><strong><?php echo htmlspecialchars($item['name_en']); ?></strong></td>
                <td><?php echo htmlspecialchars($item['position_en']); ?></td>
                <td><?php echo htmlspecialchars($item['department_en'] ?? ''); ?></td>
                <td><a href="?toggle=<?php echo $item['id']; ?>" class="badge <?php echo $item['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?> text-decoration-none"><?php echo ucfirst($item['status']); ?></a></td>
                <td><?php echo $item['sort_order']; ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-action" onclick="openEditModal(this)" data-id="<?php echo $item['id']; ?>" data-name_en="<?php echo htmlspecialchars($item['name_en']); ?>" data-name_sw="<?php echo htmlspecialchars($item['name_sw']); ?>" data-position_en="<?php echo htmlspecialchars($item['position_en']); ?>" data-position_sw="<?php echo htmlspecialchars($item['position_sw']); ?>" data-department_en="<?php echo htmlspecialchars($item['department_en'] ?? ''); ?>" data-department_sw="<?php echo htmlspecialchars($item['department_sw'] ?? ''); ?>" data-facebook_url="<?php echo htmlspecialchars($item['facebook_url'] ?? ''); ?>" data-linkedin_url="<?php echo htmlspecialchars($item['linkedin_url'] ?? ''); ?>" data-twitter_url="<?php echo htmlspecialchars($item['twitter_url'] ?? ''); ?>" data-email="<?php echo htmlspecialchars($item['email'] ?? ''); ?>" data-status="<?php echo $item['status']; ?>" data-sort_order="<?php echo $item['sort_order']; ?>" data-photo="<?php echo htmlspecialchars($item['photo'] ?? ''); ?>"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-action btn-delete-item" data-url="?delete=<?php echo $item['id']; ?>"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        <?php endwhile; ?></tbody>
    </table></div></div></div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="itemModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form method="POST" enctype="multipart/form-data" id="teamForm">
        <input type="hidden" name="id" id="form_id" value="">
        <input type="hidden" name="existing_photo" id="form_existing_photo" value="">
        <div class="modal-header"><h5 class="modal-title" id="modalTitle">Add Team Member</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-4">
                <div class="col-md-6"><label class="form-label fw-semibold">Name (English) *</label><input type="text" class="form-control" name="name_en" id="form_name_en" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Name (Kiswahili)</label><input type="text" class="form-control" name="name_sw" id="form_name_sw"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Position (English) *</label><input type="text" class="form-control" name="position_en" id="form_position_en" required placeholder="e.g. Managing Director"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Position (Kiswahili)</label><input type="text" class="form-control" name="position_sw" id="form_position_sw" placeholder="e.g. Mkurugenzi Mkuu"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Department (English)</label><input type="text" class="form-control" name="department_en" id="form_department_en" placeholder="e.g. Leadership"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Department (Kiswahili)</label><input type="text" class="form-control" name="department_sw" id="form_department_sw" placeholder="e.g. Uongozi"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" class="form-control" name="email" id="form_email"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Facebook URL</label><input type="url" class="form-control" name="facebook_url" id="form_facebook_url"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">LinkedIn URL</label><input type="url" class="form-control" name="linkedin_url" id="form_linkedin_url"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Twitter URL</label><input type="url" class="form-control" name="twitter_url" id="form_twitter_url"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" id="form_status"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Sort Order</label><input type="number" class="form-control" name="sort_order" id="form_sort_order" value="0"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Photo</label><input type="file" class="form-control" name="photo" accept="image/*" id="form_photo"><div id="form_photo_preview" class="mt-2" style="display:none;"><p class="small text-muted mb-1"><i class="fas fa-image me-1"></i>Current:</p><img id="form_photo_img" src="" style="height: 70px; width: 70px; border-radius: 50%; object-fit: cover;"></div></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
</div></div></div>

<script>
var itemModal;
document.addEventListener('DOMContentLoaded', function() {
    itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
});

function openAddModal() {
    document.getElementById('teamForm').reset();
    document.getElementById('form_id').value = '';
    document.getElementById('form_existing_photo').value = '';
    document.getElementById('modalTitle').textContent = 'Add Team Member';
    document.getElementById('form_sort_order').value = '0';
    document.getElementById('form_status').value = 'active';
    var pp = document.getElementById('form_photo_preview');
    pp.style.display = 'none';
    itemModal.show();
}

function openEditModal(btn) {
    var d = btn.dataset;
    document.getElementById('teamForm').reset();
    document.getElementById('form_id').value = d.id;
    document.getElementById('form_existing_photo').value = d.photo;
    document.getElementById('form_name_en').value = d.name_en;
    document.getElementById('form_name_sw').value = d.name_sw;
    document.getElementById('form_position_en').value = d.position_en;
    document.getElementById('form_position_sw').value = d.position_sw;
    document.getElementById('form_department_en').value = d.department_en;
    document.getElementById('form_department_sw').value = d.department_sw;
    document.getElementById('form_email').value = d.email;
    document.getElementById('form_facebook_url').value = d.facebook_url;
    document.getElementById('form_linkedin_url').value = d.linkedin_url;
    document.getElementById('form_twitter_url').value = d.twitter_url;
    document.getElementById('form_status').value = d.status;
    document.getElementById('form_sort_order').value = d.sort_order;
    document.getElementById('modalTitle').textContent = 'Edit Team Member';
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
