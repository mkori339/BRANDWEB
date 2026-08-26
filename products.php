<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = t('nav_products', 'Products');
$show_breadcrumb = true;
$page_heading = t('nav_products', 'Our Products');
$breadcrumb_current = t('nav_products', 'Products');

// Check for single product view
$single_product = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND status = 'active'");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $single_product = $stmt->get_result()->fetch_assoc();
}

// Category filter
$category_filter = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Fetch products
if ($category_filter) {
    $products_result = $conn->query("SELECT * FROM products WHERE status = 'active' AND category = '" . $category_filter . "' ORDER BY sort_order ASC");
} else {
    $products_result = $conn->query("SELECT * FROM products WHERE status = 'active' ORDER BY sort_order ASC");
}

// Fetch categories
$categories = $conn->query("SELECT DISTINCT category FROM products WHERE status = 'active' AND category IS NOT NULL AND category != '' ORDER BY category");

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($single_product): ?>
        <!-- Single Product Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                        <?php if ($single_product['image'] && file_exists(__DIR__ . '/uploads/' . $single_product['image'])): ?>
                            <img src="uploads/<?php echo $single_product['image']; ?>" class="img-fluid w-100 rounded" alt="<?php echo htmlspecialchars($current_lang === 'sw' && !empty($single_product['name_sw']) ? $single_product['name_sw'] : $single_product['name_en']); ?>">
                        <?php else: ?>
                            <img src="img/commercial-1.jpg" class="img-fluid w-100 rounded" alt="Product">
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.4s">
                        <h4 class="text-primary"><?php echo htmlspecialchars($single_product['category']); ?></h4>
                        <h1 class="display-5 mb-4"><?php echo htmlspecialchars($current_lang === 'sw' && !empty($single_product['name_sw']) ? $single_product['name_sw'] : $single_product['name_en']); ?></h1>
                        <?php if ($single_product['price']): ?>
                            <h3 class="text-primary mb-4"><?php echo htmlspecialchars($single_product['price']); ?></h3>
                        <?php endif; ?>
                        <p class="mb-4"><?php echo htmlspecialchars($current_lang === 'sw' && !empty($single_product['description_sw']) ? $single_product['description_sw'] : $single_product['description_en']); ?></p>
                        <?php 
                        $features = $current_lang === 'sw' && !empty($single_product['features_sw']) ? $single_product['features_sw'] : $single_product['features_en'];
                        if ($features): ?>
                            <h5 class="mb-3"><?php echo $current_lang === 'sw' ? 'Vipengele:' : 'Features:'; ?></h5>
                            <ul class="list-unstyled mb-4">
                                <?php foreach (explode("\n", $features) as $feature): ?>
                                    <?php if (trim($feature)): ?>
                                        <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> <?php echo htmlspecialchars(trim($feature)); ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <a href="contact.php?product=<?php echo $single_product['id']; ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn btn-primary py-3 px-5"><?php echo t('products_enquiry', 'Make Enquiry'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Single Product End -->
<?php else: ?>
        <!-- Products Grid Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('products_label', 'Our Products'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('products_title', 'Quality Weighing Equipment'); ?></h1>
                    <p class="mb-0"><?php echo t('products_desc', 'Browse our wide range of high-quality weighing scales and equipment for various applications.'); ?></p>
                </div>

                <!-- Category Filter -->
                <div class="text-center mb-4">
                    <a href="products.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="btn <?php echo !$category_filter ? 'btn-primary' : 'btn-outline-primary'; ?> py-2 px-4 m-1"><?php echo $current_lang === 'sw' ? 'Zote' : 'All'; ?></a>
                    <?php if ($categories && $categories->num_rows > 0): ?>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <a href="products.php?category=<?php echo urlencode($cat['category']); ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn <?php echo $category_filter === $cat['category'] ? 'btn-primary' : 'btn-outline-primary'; ?> py-2 px-4 m-1"><?php echo htmlspecialchars($cat['category']); ?></a>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

                <div class="row g-4">
                    <?php if ($products_result && $products_result->num_rows > 0): ?>
                        <?php $i = 0; while ($product = $products_result->fetch_assoc()): ?>
                            <?php 
                            $name = $current_lang === 'sw' && !empty($product['name_sw']) ? $product['name_sw'] : $product['name_en'];
                            $desc = $current_lang === 'sw' && !empty($product['description_sw']) ? $product['description_sw'] : $product['description_en'];
                            $delay = 0.2 + ($i % 4) * 0.1;
                            ?>
                            <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                <div class="product-item h-100">
                                    <div class="position-relative overflow-hidden">
                                        <?php if ($product['image'] && file_exists(__DIR__ . '/uploads/' . $product['image'])): ?>
                                            <img src="uploads/<?php echo $product['image']; ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($name); ?>" style="height: 200px; object-fit: cover;">
                                        <?php else: ?>
                                            <img src="img/commercial-1.jpg" class="img-fluid w-100" alt="<?php echo htmlspecialchars($name); ?>" style="height: 200px; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-4">
                                        <h5 class="mb-2"><?php echo htmlspecialchars($name); ?></h5>
                                        <?php if ($product['category']): ?>
                                            <span class="text-muted small"><?php echo htmlspecialchars($product['category']); ?></span>
                                        <?php endif; ?>
                                        <?php if ($product['price']): ?>
                                            <p class="text-primary fw-bold mt-2 mb-2"><?php echo htmlspecialchars($product['price']); ?></p>
                                        <?php endif; ?>
                                        <a href="products.php?id=<?php echo $product['id']; ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn btn-primary btn-sm w-100 mt-2"><?php echo t('products_details', 'View Details'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p class="fs-5 text-muted"><?php echo $current_lang === 'sw' ? 'Hakuna bidhaa zilizopatikana.' : 'No products found.'; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Products Grid End -->
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>