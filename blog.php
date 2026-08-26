<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = t('nav_blog', 'Blog');
$show_breadcrumb = true;
$page_heading = t('nav_blog', 'Blog');
$breadcrumb_current = t('nav_blog', 'Blog');

// Check for single post view
$single_post = null;
if (isset($_GET['slug'])) {
    $slug = sanitize($_GET['slug']);
    $stmt = $conn->prepare("SELECT bp.*, a.full_name as author_name FROM blog_posts bp LEFT JOIN admins a ON bp.author_id = a.id WHERE bp.slug = ? AND bp.status = 'published'");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $single_post = $stmt->get_result()->fetch_assoc();
    if ($single_post) {
        // Increment views
        $conn->query("UPDATE blog_posts SET views = views + 1 WHERE id = " . $single_post['id']);
    }
}

// Category filter
$category_filter = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Search
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

if ($single_post) {
    // Single post view - get related posts
    $related_result = $conn->query("SELECT * FROM blog_posts WHERE status = 'published' AND id != " . $single_post['id'] . " ORDER BY created_at DESC LIMIT 3");
}

// Get posts
$where = "WHERE status = 'published'";
if ($category_filter) {
    $where .= " AND category = '" . $category_filter . "'";
}
if ($search) {
    $where .= " AND (title_en LIKE '%" . $search . "%' OR title_sw LIKE '%" . $search . "%' OR content_en LIKE '%" . $search . "%' OR content_sw LIKE '%" . $search . "%')";
}

$blog_result = $conn->query("SELECT bp.*, a.full_name as author_name FROM blog_posts bp LEFT JOIN admins a ON bp.author_id = a.id $where ORDER BY created_at DESC");

// Get categories
$blog_categories = $conn->query("SELECT category, COUNT(*) as count FROM blog_posts WHERE status = 'published' GROUP BY category ORDER BY category");

// Get recent posts
$recent_result = $conn->query("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 5");

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($single_post): ?>
        <!-- Single Blog Post Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <?php if ($single_post['featured_image'] && file_exists(__DIR__ . '/uploads/' . $single_post['featured_image'])): ?>
                                <img src="uploads/<?php echo $single_post['featured_image']; ?>" class="img-fluid w-100 rounded mb-4" alt="<?php echo htmlspecialchars($current_lang === 'sw' && !empty($single_post['title_sw']) ? $single_post['title_sw'] : $single_post['title_en']); ?>">
                            <?php else: ?>
                                <img src="img/blog-placeholder.svg" class="img-fluid w-100 rounded mb-4" alt="<?php echo htmlspecialchars($current_lang === 'sw' && !empty($single_post['title_sw']) ? $single_post['title_sw'] : $single_post['title_en']); ?>">
                            <?php endif; ?>
                            <?php if ($single_post['category']): ?>
                                <span class="bg-primary text-white px-3 py-1 rounded"><?php echo htmlspecialchars($single_post['category']); ?></span>
                            <?php endif; ?>
                            <h1 class="display-5 mt-3"><?php echo htmlspecialchars($current_lang === 'sw' && !empty($single_post['title_sw']) ? $single_post['title_sw'] : $single_post['title_en']); ?></h1>
                            <div class="d-flex align-items-center text-muted mb-4">
                                <span class="me-3"><i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($single_post['author_name'] ?? 'Admin'); ?></span>
                                <span class="me-3"><i class="fas fa-calendar me-1"></i> <?php echo date('F d, Y', strtotime($single_post['created_at'])); ?></span>
                                <span><i class="fas fa-eye me-1"></i> <?php echo $single_post['views']; ?> <?php echo $current_lang === 'sw' ? 'maoni' : 'views'; ?></span>
                            </div>
                        </div>
                        <div class="blog-content">
                            <?php echo $current_lang === 'sw' && !empty($single_post['content_sw']) ? $single_post['content_sw'] : $single_post['content_en']; ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <!-- Search -->
                        <div class="mb-4 p-4 bg-light rounded">
                            <h5 class="mb-3"><?php echo t('blog_search', 'Search...'); ?></h5>
                            <form method="GET" action="blog.php">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="<?php echo t('blog_search', 'Search...'); ?>" value="<?php echo htmlspecialchars($search); ?>">
                                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                                <?php echo $current_lang !== 'en' ? '<input type="hidden" name="lang" value="' . $current_lang . '">' : ''; ?>
                            </form>
                        </div>
                        <!-- Categories -->
                        <div class="mb-4 p-4 bg-light rounded">
                            <h5 class="mb-3"><?php echo t('blog_categories', 'Categories'); ?></h5>
                            <?php if ($blog_categories && $blog_categories->num_rows > 0): ?>
                                <ul class="list-unstyled">
                                    <?php while ($cat = $blog_categories->fetch_assoc()): ?>
                                        <li class="mb-2">
                                            <a href="blog.php?category=<?php echo urlencode($cat['category']); ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="text-decoration-none">
                                                <i class="fas fa-chevron-right text-primary me-2"></i><?php echo htmlspecialchars($cat['category']); ?>
                                                <span class="badge bg-primary rounded-pill float-end"><?php echo $cat['count']; ?></span>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <!-- Recent Posts -->
                        <div class="mb-4 p-4 bg-light rounded">
                            <h5 class="mb-3"><?php echo t('blog_recent_posts', 'Recent Posts'); ?></h5>
                            <?php if ($recent_result && $recent_result->num_rows > 0): ?>
                                <?php while ($recent = $recent_result->fetch_assoc()): ?>
                                    <div class="d-flex mb-3">
                                        <?php if ($recent['featured_image'] && file_exists(__DIR__ . '/uploads/' . $recent['featured_image'])): ?>
                                            <img src="uploads/<?php echo $recent['featured_image']; ?>" class="rounded me-3" alt="" style="width: 70px; height: 70px; object-fit: contain;">
                                        <?php endif; ?>
                                        <div>
                                            <a href="blog.php?slug=<?php echo $recent['slug']; ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="text-decoration-none">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($current_lang === 'sw' && !empty($recent['title_sw']) ? $recent['title_sw'] : $recent['title_en']); ?></h6>
                                            </a>
                                            <small class="text-muted"><?php echo date('M d, Y', strtotime($recent['created_at'])); ?></small>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Single Blog Post End -->
<?php else: ?>
        <!-- Blog List Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('blog_label', 'Latest News'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('blog_title', 'Industry Insights & Updates'); ?></h1>
                    <p class="mb-0"><?php echo t('blog_desc', 'Stay informed with the latest news, articles and insights from the weighing industry.'); ?></p>
                </div>

                <div class="row g-5">
                    <div class="col-lg-8">
                        <div class="row g-4">
                            <?php if ($blog_result && $blog_result->num_rows > 0): ?>
                                <?php $i = 0; while ($post = $blog_result->fetch_assoc()): ?>
                                    <?php 
                                    $title = $current_lang === 'sw' && !empty($post['title_sw']) ? $post['title_sw'] : $post['title_en'];
                                    $excerpt = $current_lang === 'sw' && !empty($post['excerpt_sw']) ? $post['excerpt_sw'] : $post['excerpt_en'];
                                    $delay = 0.2 + ($i % 3) * 0.1;
                                    ?>
                                    <div class="col-md-6 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <?php if ($post['featured_image'] && file_exists(__DIR__ . '/uploads/' . $post['featured_image'])): ?>
                                                <img src="uploads/<?php echo $post['featured_image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($title); ?>" style="object-fit: contain;">
                                            <?php else: ?>
                                                <img src="img/blog-placeholder.svg" class="card-img-top" alt="<?php echo htmlspecialchars($title); ?>" style="object-fit: contain;">
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <?php if ($post['category']): ?>
                                                    <span class="bg-primary text-white px-2 py-1 rounded small mb-2 d-inline-block"><?php echo htmlspecialchars($post['category']); ?></span>
                                                <?php endif; ?>
                                                <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                                                <p class="card-text"><?php echo htmlspecialchars(substr($excerpt, 0, 120)); ?>...</p>
                                            </div>
                                            <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                                                <small class="text-muted"><i class="fas fa-calendar me-1"></i> <?php echo date('M d, Y', strtotime($post['created_at'])); ?></small>
                                                <a href="blog.php?slug=<?php echo $post['slug']; ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn btn-sm btn-primary"><?php echo t('blog_read_more', 'Read More'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php $i++; endwhile; ?>
                            <?php else: ?>
                                <div class="col-12 text-center">
                                    <p class="fs-5 text-muted"><?php echo t('blog_no_posts', 'No blog posts found.'); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <!-- Search -->
                        <div class="mb-4 p-4 bg-light rounded">
                            <h5 class="mb-3"><?php echo t('blog_search', 'Search...'); ?></h5>
                            <form method="GET" action="blog.php">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="<?php echo t('blog_search', 'Search...'); ?>" value="<?php echo htmlspecialchars($search); ?>">
                                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                                <?php echo $current_lang !== 'en' ? '<input type="hidden" name="lang" value="' . $current_lang . '">' : ''; ?>
                            </form>
                        </div>
                        <!-- Categories -->
                        <div class="mb-4 p-4 bg-light rounded">
                            <h5 class="mb-3"><?php echo t('blog_categories', 'Categories'); ?></h5>
                            <?php if ($blog_categories && $blog_categories->num_rows > 0): ?>
                                <ul class="list-unstyled">
                                    <?php while ($cat = $blog_categories->fetch_assoc()): ?>
                                        <li class="mb-2">
                                            <a href="blog.php?category=<?php echo urlencode($cat['category']); ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="text-decoration-none">
                                                <i class="fas fa-chevron-right text-primary me-2"></i><?php echo htmlspecialchars($cat['category']); ?>
                                                <span class="badge bg-primary rounded-pill float-end"><?php echo $cat['count']; ?></span>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <!-- Recent Posts -->
                        <div class="mb-4 p-4 bg-light rounded">
                            <h5 class="mb-3"><?php echo t('blog_recent_posts', 'Recent Posts'); ?></h5>
                            <?php if ($recent_result && $recent_result->num_rows > 0): ?>
                                <?php while ($recent = $recent_result->fetch_assoc()): ?>
                                    <div class="d-flex mb-3">
                                        <?php if ($recent['featured_image'] && file_exists(__DIR__ . '/uploads/' . $recent['featured_image'])): ?>
                                            <img src="uploads/<?php echo $recent['featured_image']; ?>" class="rounded me-3" alt="" style="width: 70px; height: 70px; object-fit: contain;">
                                        <?php endif; ?>
                                        <div>
                                            <a href="blog.php?slug=<?php echo $recent['slug']; ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="text-decoration-none">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($current_lang === 'sw' && !empty($recent['title_sw']) ? $recent['title_sw'] : $recent['title_en']); ?></h6>
                                            </a>
                                            <small class="text-muted"><?php echo date('M d, Y', strtotime($recent['created_at'])); ?></small>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Blog List End -->
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>