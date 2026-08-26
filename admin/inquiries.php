<?php
$admin_page_title = 'Contact Inquiries';
require_once __DIR__ . '/includes/header.php';

if (isset($_GET['delete'])) { $conn->query("DELETE FROM inquiries WHERE id = " . intval($_GET['delete'])); setFlash('success', 'Deleted.'); redirect(ADMIN_URL . '/inquiries.php'); }
if (isset($_GET['mark_read'])) { $conn->query("UPDATE inquiries SET is_read = 1 WHERE id = " . intval($_GET['mark_read'])); redirect(ADMIN_URL . '/inquiries.php'); }

$inquiries = $conn->query("SELECT * FROM inquiries ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Contact Inquiries</h4>
    <span class="badge bg-primary"><?php echo $inquiries->num_rows; ?> total</span>
</div>

<div class="card table-card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody><?php $i=1; while ($item = $inquiries->fetch_assoc()): ?>
            <tr <?php echo !$item['is_read'] ? 'class="table-light fw-bold"' : ''; ?>>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><a href="mailto:<?php echo htmlspecialchars($item['email']); ?>"><?php echo htmlspecialchars($item['email']); ?></a></td>
                <td><?php echo htmlspecialchars($item['phone'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($item['subject'] ?? 'N/A'); ?></td>
                <td><?php echo date('M d, Y H:i', strtotime($item['created_at'])); ?></td>
                <td><?php if (!$item['is_read']): ?><a href="?mark_read=<?php echo $item['id']; ?>" class="badge bg-danger text-decoration-none">New</a><?php else: ?><span class="badge bg-success">Read</span><?php endif; ?></td>
                <td>
                    <a href="mailto:<?php echo htmlspecialchars($item['email']); ?>?subject=Re: <?php echo urlencode($item['subject'] ?? ''); ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-reply"></i></a>
                    <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <tr><td colspan="8" class="bg-light"><small class="text-muted">Message:</small><br><?php echo nl2br(htmlspecialchars($item['message'])); ?></td></tr>
        <?php endwhile; ?></tbody>
    </table></div></div></div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>