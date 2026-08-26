<?php
$admin_page_title = 'Videos (YouTube)';
require_once __DIR__ . '/includes/header.php';

// Helper to extract YouTube ID
function extractYouTubeId($url) {
    $patterns = [
        '/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]+)/',
        '/(?:youtu\.be\/)([a-zA-Z0-9_-]+)/',
        '/(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/',
        '/(?:youtube\.com\/shorts\/)([a-zA-Z0-9_-]+)/',
        '/(?:youtube\.com\/v\/)([a-zA-Z0-9_-]+)/',
        '/(?:youtube\.com\/live\/)([a-zA-Z0-9_-]+)/',
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
    }
    // Fallback: try to extract any 11-char video ID from query params
    if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    return null;
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM media_videos WHERE id = " . intval($_GET['delete']));
    setFlash('success', 'Video deleted.');
    redirect(ADMIN_URL . '/videos.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $title_en = sanitize($_POST['title_en']);
    $title_sw = sanitize($_POST['title_sw']);
    $desc_en = sanitize($_POST['description_en']);
    $desc_sw = sanitize($_POST['description_sw']);
    $youtube_url = sanitize($_POST['youtube_url']);
    $category = sanitize($_POST['category']);
    $status = sanitize($_POST['status']);
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $youtube_id = extractYouTubeId($youtube_url);
    
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE media_videos SET title_en=?, title_sw=?, description_en=?, description_sw=?, youtube_url=?, youtube_id=?, category=?, status=?, sort_order=? WHERE id=?");
        $stmt->bind_param("ssssssssii", $title_en, $title_sw, $desc_en, $desc_sw, $youtube_url, $youtube_id, $category, $status, $sort_order, $id);
        $stmt->execute();
        setFlash('success', 'Video updated.');
    } else {
        $stmt = $conn->prepare("INSERT INTO media_videos (title_en, title_sw, description_en, description_sw, youtube_url, youtube_id, category, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $title_en, $title_sw, $desc_en, $desc_sw, $youtube_url, $youtube_id, $category, $status, $sort_order);
        $stmt->execute();
        setFlash('success', 'Video added.');
    }
    redirect(ADMIN_URL . '/videos.php');
}

$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_item = $conn->query("SELECT * FROM media_videos WHERE id = " . intval($_GET['edit']))->fetch_assoc();
}

$items = $conn->query("SELECT * FROM media_videos ORDER BY sort_order ASC, created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><?php echo $edit_item ? 'Edit Video' : 'Manage Videos'; ?></h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#videoModal"><i class="fas fa-plus me-1"></i> Add Video</button>
</div>

<p class="text-muted">Add company event videos by pasting a YouTube link. Videos are streamed directly from YouTube.</p>

<?php if (!$edit_item): ?>
<div class="card table-card"><div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>#</th><th>Preview</th><th>Title</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php $i = 1; while ($item = $items->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php if ($item['youtube_id']): ?><img src="https://img.youtube.com/vi/<?php echo $item['youtube_id']; ?>/mqdefault.jpg" alt="" style="height: 60px; border-radius: 4px;"><?php endif; ?></td>
                        <td><strong><?php echo htmlspecialchars($item['title_en'] ?? 'Untitled'); ?></strong></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></span></td>
                        <td><span class="badge <?php echo $item['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>"><?php echo ucfirst($item['status']); ?></span></td>
                        <td>
                            <a href="?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div></div>
<?php endif; ?>

<?php if ($edit_item): ?>
<div class="card form-card"><div class="card-header d-flex justify-content-between"><h5 class="mb-0">Edit Video</h5><a href="<?php echo ADMIN_URL; ?>/videos.php" class="btn btn-sm btn-outline-secondary">Cancel</a></div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>">
            <div class="row g-4">
                <div class="col-md-6"><label class="form-label fw-semibold">Title (English)</label><input type="text" class="form-control" name="title_en" value="<?php echo htmlspecialchars($edit_item['title_en']); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Title (Kiswahili)</label><input type="text" class="form-control" name="title_sw" value="<?php echo htmlspecialchars($edit_item['title_sw']); ?>"></div>
                <div class="col-12"><label class="form-label fw-semibold">YouTube URL *</label><input type="url" class="form-control" name="youtube_url" required value="<?php echo htmlspecialchars($edit_item['youtube_url']); ?>" placeholder="https://www.youtube.com/watch?v=..."></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Category</label><input type="text" class="form-control" name="category" value="<?php echo htmlspecialchars($edit_item['category']); ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status"><option value="active" <?php echo $edit_item['status'] === 'active' ? 'selected' : ''; ?>>Active</option><option value="inactive" <?php echo $edit_item['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option></select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Sort Order</label><input type="number" class="form-control" name="sort_order" value="<?php echo $edit_item['sort_order']; ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Description (English)</label><textarea class="form-control" name="description_en" rows="3"><?php echo htmlspecialchars($edit_item['description_en']); ?></textarea></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Description (Kiswahili)</label><textarea class="form-control" name="description_sw" rows="3"><?php echo htmlspecialchars($edit_item['description_sw']); ?></textarea></div>
                <div class="col-12"><button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-1"></i> Save Video</button></div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="modal fade" id="videoModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form method="POST">
        <div class="modal-header"><h5 class="modal-title">Add YouTube Video</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-4">
                <div class="col-12"><label class="form-label fw-semibold">YouTube URL * <small class="text-muted">(Paste any YouTube link)</small></label><input type="url" class="form-control" name="youtube_url" required placeholder="https://www.youtube.com/watch?v=..."></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Title (English)</label><input type="text" class="form-control" name="title_en"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Title (Kiswahili)</label><input type="text" class="form-control" name="title_sw"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Description (English)</label><textarea class="form-control" name="description_en" rows="2"></textarea></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Description (Kiswahili)</label><textarea class="form-control" name="description_sw" rows="2"></textarea></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Category</label><input type="text" class="form-control" name="category" placeholder="e.g., Event, Tutorial"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Sort Order</label><input type="number" class="form-control" name="sort_order" value="0"></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Video</button></div>
    </form>
</div></div></div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>