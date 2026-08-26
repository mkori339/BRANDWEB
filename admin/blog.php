<?php
$admin_page_title = 'Blog Posts';
require_once __DIR__ . '/includes/header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM blog_posts WHERE id = $id");
    setFlash('success', 'Blog post deleted successfully.');
    redirect(ADMIN_URL . '/blog.php');
}

// Handle status toggle
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $conn->query("UPDATE blog_posts SET status = IF(status='published','draft','published') WHERE id = $id");
    setFlash('success', 'Status updated successfully.');
    redirect(ADMIN_URL . '/blog.php');
}

// Handle form submission (add/edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $title_en = sanitize($_POST['title_en']);
    $title_sw = sanitize($_POST['title_sw']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title_en), '-'));
    $excerpt_en = sanitize($_POST['excerpt_en']);
    $excerpt_sw = sanitize($_POST['excerpt_sw']);
    $content_en = $_POST['content_en'];
    $content_sw = $_POST['content_sw'];
    $category = sanitize($_POST['category']);
    $status = sanitize($_POST['status']);
    $author_id = $_SESSION['admin_id'];
    
    // Handle image upload
    $featured_image = $_POST['existing_image'] ?? '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '', basename($_FILES['featured_image']['name']));
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $upload_dir = __DIR__ . '/../uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $upload_dir . $filename)) {
                $featured_image = $filename;
            }
        }
    }
    
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE blog_posts SET title_en=?, title_sw=?, slug=?, excerpt_en=?, excerpt_sw=?, content_en=?, content_sw=?, featured_image=?, category=?, status=? WHERE id=?");
        $stmt->bind_param("ssssssssssi", $title_en, $title_sw, $slug, $excerpt_en, $excerpt_sw, $content_en, $content_sw, $featured_image, $category, $status, $id);
        $stmt->execute();
        setFlash('success', 'Blog post updated successfully.');
    } else {
        // Check slug uniqueness
        $check = $conn->query("SELECT id FROM blog_posts WHERE slug = '$slug'");
        if ($check->num_rows > 0) {
            $slug .= '-' . time();
        }
        $stmt = $conn->prepare("INSERT INTO blog_posts (title_en, title_sw, slug, excerpt_en, excerpt_sw, content_en, content_sw, featured_image, category, status, author_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $title_en, $title_sw, $slug, $excerpt_en, $excerpt_sw, $content_en, $content_sw, $featured_image, $category, $status, $author_id);
        $stmt->execute();
        setFlash('success', 'Blog post created successfully.');
    }
    redirect(ADMIN_URL . '/blog.php');
}

// Edit mode
$edit_post = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_post = $conn->query("SELECT * FROM blog_posts WHERE id = $edit_id")->fetch_assoc();
}

$posts = $conn->query("SELECT bp.*, a.full_name as author_name FROM blog_posts bp LEFT JOIN admins a ON bp.author_id = a.id ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><?php echo $edit_post ? 'Edit Blog Post' : 'Manage Blog Posts'; ?></h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#blogModal" onclick="resetForm()">
        <i class="fas fa-plus me-1"></i> Add New Post
    </button>
</div>

<?php if (!$edit_post): ?>
<!-- Posts Table -->
<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; while ($post = $posts->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo htmlspecialchars($post['title_en']); ?></strong></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($post['category'] ?? 'Uncategorized'); ?></span></td>
                            <td><?php echo htmlspecialchars($post['author_name'] ?? 'Admin'); ?></td>
                            <td><?php echo $post['views']; ?></td>
                            <td>
                                <a href="?toggle=<?php echo $post['id']; ?>" class="badge <?php echo $post['status'] === 'published' ? 'bg-success' : 'bg-warning'; ?> text-decoration-none">
                                    <?php echo ucfirst($post['status']); ?>
                                </a>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                            <td>
                                <a href="?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary btn-action"><i class="fas fa-edit"></i></a>
                                <a href="?delete=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-danger btn-action btn-delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Edit Form (inline) -->
<?php if ($edit_post): ?>
<div class="card form-card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Edit: <?php echo htmlspecialchars($edit_post['title_en']); ?></h5>
        <a href="<?php echo ADMIN_URL; ?>/blog.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $edit_post['id']; ?>">
            <input type="hidden" name="existing_image" value="<?php echo $edit_post['featured_image']; ?>">
            <?php include __DIR__ . '/_blog_form_fields.php'; ?>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Add New Modal -->
<div class="modal fade" id="blogModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Blog Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php include __DIR__ . '/_blog_form_fields.php'; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>