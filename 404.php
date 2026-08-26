<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = 'Page Not Found';
$show_breadcrumb = false;

require_once __DIR__ . '/includes/header.php';
?>

<!-- 404 Start -->
<div class="container-fluid py-5">
    <div class="container py-5 text-center">
        <div class="mb-4">
            <i class="fas fa-exclamation-triangle fa-5x text-primary"></i>
        </div>
        <h1 class="display-1 fw-bold text-primary">404</h1>
        <h2 class="mb-4"><?php echo t('error_404_title', 'Page Not Found'); ?></h2>
        <p class="fs-5 mb-5 text-muted"><?php echo t('error_404_desc', 'Sorry, the page you are looking for does not exist or has been moved.'); ?></p>
        <a href="index.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="btn btn-primary py-3 px-5">
            <i class="fas fa-home me-2"></i> <?php echo t('error_404_btn', 'Back to Home'); ?>
        </a>
    </div>
</div>
<!-- 404 End -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>