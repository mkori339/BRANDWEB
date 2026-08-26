<?php
$admin_page_title = 'Newsletter Subscribers';
require_once __DIR__ . '/includes/header.php';

if (isset($_GET['delete'])) { $conn->query("DELETE FROM subscribers WHERE id = " . intval($_GET['delete'])); setFlash('success', 'Deleted.'); redirect(ADMIN_URL . '/subscribers.php'); }
if (isset($_GET['toggle'])) { $conn->query("UPDATE subscribers SET status = IF(status='active','inactive','active') WHERE id = " . intval($_GET['toggle'])); setFlash('success', 'Status updated.'); redirect(ADMIN_URL . '/subscribers.php'); }

$items = $conn->query("SELECT * FROM subscribers ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Newsletter Subscribers</h4>
    <span class="badge bg-primary"><?php echo $items->num_rows; ?> subscribers</span>
</div>

<div class="card table-card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>#</th><th>Email</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody><?php $i=1; while ($item = $items->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($item['email']); ?></strong></td>
                <td><a href="?toggle=<?php echo $item['id']; ?>" class="badge <?php echo $item['status']==='active'?'bg-success':'bg-secondary'; ?> text-decoration-none"><?php echo ucfirst($item['status']); ?></a></td>
                <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                <td><a href="?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete"><i class="fas fa-trash"></i></a></td>
            </tr>
        <?php endwhile; ?></tbody>
    </table></div></div></div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>