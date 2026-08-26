<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = t('nav_projects', 'Projects');
$show_breadcrumb = true;
$page_heading = t('nav_projects', 'Our Projects');
$breadcrumb_current = t('nav_projects', 'Projects');

// Category filter
$category_filter = isset($_GET['category']) ? sanitize($_GET['category']) : '';

if ($category_filter) {
    $projects_result = $conn->query("SELECT * FROM projects WHERE status = 'active' AND category = '" . $category_filter . "' ORDER BY sort_order ASC");
} else {
    $projects_result = $conn->query("SELECT * FROM projects WHERE status = 'active' ORDER BY sort_order ASC");
}

$categories = $conn->query("SELECT DISTINCT category FROM projects WHERE status = 'active' AND category IS NOT NULL AND category != '' ORDER BY category");

require_once __DIR__ . '/includes/header.php';
?>

        <!-- Projects Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('projects_label', 'Our Portfolio'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('projects_title', 'Completed Projects'); ?></h1>
                    <p class="mb-0"><?php echo t('projects_desc', 'Take a look at some of our successful weighing scale installations and projects across Tanzania.'); ?></p>
                </div>

                <!-- Category Filter -->
                <div class="text-center mb-4">
                    <a href="projects.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="btn <?php echo !$category_filter ? 'btn-primary' : 'btn-outline-primary'; ?> py-2 px-4 m-1"><?php echo $current_lang === 'sw' ? 'Zote' : 'All'; ?></a>
                    <?php if ($categories && $categories->num_rows > 0): ?>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <a href="projects.php?category=<?php echo urlencode($cat['category']); ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn <?php echo $category_filter === $cat['category'] ? 'btn-primary' : 'btn-outline-primary'; ?> py-2 px-4 m-1"><?php echo htmlspecialchars($cat['category']); ?></a>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

                <div class="row g-4">
                    <?php if ($projects_result && $projects_result->num_rows > 0): ?>
                        <?php $i = 0; while ($project = $projects_result->fetch_assoc()): ?>
                            <?php 
                            $title = $current_lang === 'sw' && !empty($project['title_sw']) ? $project['title_sw'] : $project['title_en'];
                            $desc = $current_lang === 'sw' && !empty($project['description_sw']) ? $project['description_sw'] : $project['description_en'];
                            $client = $current_lang === 'sw' && !empty($project['client_sw']) ? $project['client_sw'] : $project['client_en'];
                            $delay = 0.2 + ($i % 3) * 0.1;
                            ?>
                            <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="position-relative overflow-hidden">
                                        <?php if ($project['image'] && file_exists(__DIR__ . '/uploads/' . $project['image'])): ?>
                                            <img src="uploads/<?php echo $project['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($title); ?>" style="height: 250px; object-fit: cover;">
                                        <?php else: ?>
                                            <img src="img/commercial-1.jpg" class="card-img-top" alt="Project" style="height: 250px; object-fit: cover;">
                                        <?php endif; ?>
                                        <?php if ($project['category']): ?>
                                            <span class="position-absolute top-0 start-0 bg-primary text-white px-3 py-1 m-2 rounded"><?php echo htmlspecialchars($project['category']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars(substr($desc, 0, 150)); ?>...</p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <?php if ($client): ?>
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                <small><?php echo t('projects_client', 'Client'); ?>: <?php echo htmlspecialchars($client); ?></small>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($project['location']): ?>
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                <small><?php echo t('projects_location', 'Location'); ?>: <?php echo htmlspecialchars($project['location']); ?></small>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($project['completion_date']): ?>
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-calendar text-primary me-2"></i>
                                                <small><?php echo t('projects_date', 'Completed'); ?>: <?php echo htmlspecialchars($project['completion_date']); ?></small>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php $i++; endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p class="fs-5 text-muted"><?php echo $current_lang === 'sw' ? 'Hakuna miradi iliyopatikana.' : 'No projects found.'; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Projects End -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>