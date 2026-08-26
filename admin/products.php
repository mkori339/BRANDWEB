<?php
$admin_page_title = 'Products';
require_once __DIR__ . '/includes/header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id = $id");
    setFlash('success', 'Product deleted successfully.');
    redirect(ADMIN_URL . '/products.php');
}

// Handle toggle featured
if (isset($_GET['toggle_feature'])) {
    $id = intval($_GET['toggle_feature']);
    $conn->query("UPDATE products SET is_featured = IF(is_featured=1,0,1) WHERE id = $id");
    setFlash('success', 'Featured status updated.');
    redirect(ADMIN_URL . '/products.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name_en = sanitize($_POST['name_en']);
    $name_sw = sanitize($_POST['name_sw']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name_en), '-'));
    $description_en = sanitize($_POST['description_en']);
    $description_sw = sanitize($_POST['description_sw']);
    $features_en = sanitize($_POST['features_en']);
    $features_sw = sanitize($_POST['features_sw']);
    $category = sanitize($_POST['category']);
    $price = sanitize($_POST['price']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $status = sanitize($_POST['status']);
    $sort_order = intval($_POST['sort_order'] ?? 0);
    
    $image = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '', basename($_FILES['image']['name']));
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $upload_dir = __DIR__ . '/../uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
                $image = $filename;
            }
        }
    }
    
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE products SET name_en=?, name_sw=?, slug=?, description_en=?, description_sw=?, features_en=?, features_sw=?, image=?, category=?, price=?, is_featured=?, status=?, sort_order=? WHERE id=?");
        $stmt->bind_param("ssssssssssssii", $name_en, $name_sw, $slug, $description_en, $description_sw, $features_en, $features_sw, $image, $category, $price, $is_featured, $status, $sort_order, $id);
        $stmt->execute();
        setFlash('success', 'Product updated successfully.');
    } else {
        $check = $conn->query("SELECT id FROM products WHERE slug = '$slug'");
        if ($check->num_rows > 0) $slug .= '-' . time();
        $stmt = $conn->prepare("INSERT INTO products (name_en, name_sw, slug, description_en, description_sw, features_en, features_sw, image, category, price, is_featured, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssi", $name_en, $name_sw, $slug, $description_en, $description_sw, $features_en, $features_sw, $image, $category, $price, $is_featured, $status, $sort_order);
        $stmt->execute();
        setFlash('success', 'Product created successfully.');
    }
    redirect(ADMIN_URL . '/products.php');
}

$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_item = $conn->query("SELECT * FROM products WHERE id = " . intval($_GET['edit']))->fetch_assoc();
}

$items = $conn->query("SELECT * FROM products ORDER BY sort_order ASC, created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><?php echo $edit_item ? 'Edit Product' : 'Manage Products'; ?></h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal"><i class="fas fa-plus me-1"></i> Add New Product</button>
</div>

<?php if (!$edit_item): ?>
<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Name</th><th>Category</th><th>Price</th><th>Featured</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php $i = 1; while ($item = $items->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo htmlspecialchars($item['name_en']); ?></strong></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></span></td>
                            <td><?php echo htmlspecialchars($item['price'] ?? 'N/A'); ?></td>
                            <td><a href="?toggle_feature=<?php echo $item['id']; ?>" class="badge <?php echo $item['is_featured'] ? 'bg-warning' : 'bg-light text-dark'; ?> text-decoration-none"><?php echo $item['is_featured'] ? 'Featured' : 'Regular'; ?></a></td>
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
    </div>
</div>
<?php endif; ?>

<?php if ($edit_item): ?>
<div class="card form-card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Edit: <?php echo htmlspecialchars($edit_item['name_en']); ?></h5>
        <a href="<?php echo ADMIN_URL; ?>/products.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>">
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($edit_item['image'] ?? ''); ?>">
            <?php include __DIR__ . '/_product_form_fields.php'; ?>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-xl"><div class="modal-content">
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-header"><h5 class="modal-title">Add New Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><?php include __DIR__ . '/_product_form_fields.php'; ?></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Product</button></div>
        </form>
    </div></div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>