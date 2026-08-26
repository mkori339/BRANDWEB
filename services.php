<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = t('nav_services', 'Services');
$show_breadcrumb = true;
$page_heading = t('nav_services', 'Our Services');
$breadcrumb_current = t('nav_services', 'Services');

// Check for single service view
$single_service = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ? AND status = 'active'");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $single_service = $stmt->get_result()->fetch_assoc();
    if ($single_service) {
        $page_title = $current_lang === 'sw' && !empty($single_service['title_sw']) ? $single_service['title_sw'] : $single_service['title_en'];
        $breadcrumb_current = $page_title;
    }
}

// Fetch all services (for grid view and related services)
$services_result = $conn->query("SELECT * FROM services WHERE status = 'active' ORDER BY sort_order ASC");

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($single_service): ?>
        <!-- Single Service Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                        <?php
                        $s_title = $current_lang === 'sw' && !empty($single_service['title_sw']) ? $single_service['title_sw'] : $single_service['title_en'];
                        $s_desc = $current_lang === 'sw' && !empty($single_service['description_sw']) ? $single_service['description_sw'] : $single_service['description_en'];
                        $s_icon = !empty($single_service['icon']) ? $single_service['icon'] : 'fas fa-cog';
                        ?>
                        <?php if ($single_service['image'] && file_exists(__DIR__ . '/uploads/' . $single_service['image'])): ?>
                            <img src="uploads/<?php echo $single_service['image']; ?>" class="img-fluid w-100 rounded" alt="<?php echo htmlspecialchars($s_title); ?>">
                        <?php else: ?>
                            <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="min-height: 300px;">
                                <i class="<?php echo $s_icon; ?> fa-5x text-primary"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.4s">
                        <div class="mb-3">
                            <i class="<?php echo $s_icon; ?> fa-3x text-primary"></i>
                        </div>
                        <h1 class="display-5 mb-4"><?php echo htmlspecialchars($s_title); ?></h1>
                        <p class="fs-5 mb-4"><?php echo nl2br(htmlspecialchars($s_desc)); ?></p>
                        <a href="contact.php?service=<?php echo urlencode($single_service['title_en']); ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn btn-primary py-3 px-5"><?php echo t('services_enquiry', 'Inquire About This Service'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Single Service End -->

        <!-- Related Services Start -->
        <div class="container-fluid service py-5 bg-light">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('services_label', 'Our Services'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('services_title', 'Comprehensive Weighing Solutions'); ?></h1>
                </div>
                <div class="row g-4">
                    <?php if ($services_result && $services_result->num_rows > 0): ?>
                        <?php $i = 0; while ($service = $services_result->fetch_assoc()): ?>
                            <?php
                            $title = $current_lang === 'sw' && !empty($service['title_sw']) ? $service['title_sw'] : $service['title_en'];
                            $desc = $current_lang === 'sw' && !empty($service['description_sw']) ? $service['description_sw'] : $service['description_en'];
                            $icon = !empty($service['icon']) ? $service['icon'] : 'fas fa-cog';
                            ?>
                            <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="<?php echo (0.2 + $i * 0.2); ?>s">
                                <div class="service-item h-100">
                                    <?php if ($service['image'] && file_exists(__DIR__ . '/uploads/' . $service['image'])): ?>
                                        <img src="uploads/<?php echo $service['image']; ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($title); ?>" style="object-fit: contain;">
                                    <?php endif; ?>
                                    <div class="border border-top-0 p-4 h-100">
                                        <div class="mb-3">
                                            <i class="<?php echo $icon; ?> fa-3x text-primary"></i>
                                        </div>
                                        <h4 class="mb-3"><?php echo htmlspecialchars($title); ?></h4>
                                        <p class="mb-4"><?php echo htmlspecialchars(substr($desc, 0, 120)); ?>...</p>
                                        <a href="services.php?id=<?php echo $service['id']; ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn btn-primary py-2 px-4"><?php echo t('services_readmore', 'Read More'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Related Services End -->

<?php else: ?>
        <!-- Services Grid Start -->
        <div class="container-fluid service py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('services_label', 'Our Services'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('services_title', 'Comprehensive Weighing Solutions'); ?></h1>
                    <p class="mb-0"><?php echo t('services_desc', 'We offer end-to-end weighing scale solutions tailored to meet the needs of various industries.'); ?></p>
                </div>
                <div class="row g-5">
                    <?php if ($services_result && $services_result->num_rows > 0): ?>
                        <?php $i = 0; while ($service = $services_result->fetch_assoc()): ?>
                            <?php
                            $title = $current_lang === 'sw' && !empty($service['title_sw']) ? $service['title_sw'] : $service['title_en'];
                            $desc = $current_lang === 'sw' && !empty($service['description_sw']) ? $service['description_sw'] : $service['description_en'];
                            $icon = !empty($service['icon']) ? $service['icon'] : 'fas fa-cog';
                            $delay = 0.2 + ($i % 3) * 0.1;
                            ?>
                            <div class="col-lg-4 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                <div class="service-item h-100 p-4 rounded shadow-sm">
                                    <?php if ($service['image'] && file_exists(__DIR__ . '/uploads/' . $service['image'])): ?>
                                        <img src="uploads/<?php echo $service['image']; ?>" class="img-fluid w-100 mb-3" alt="<?php echo htmlspecialchars($title); ?>" style="object-fit: contain;">
                                    <?php endif; ?>
                                    <div class="mb-3">
                                        <i class="<?php echo $icon; ?> fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="mb-3"><?php echo htmlspecialchars($title); ?></h4>
                                    <p class="mb-4"><?php echo htmlspecialchars($desc); ?></p>
                                    <a href="services.php?id=<?php echo $service['id']; ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn btn-primary py-2 px-4"><?php echo t('services_readmore', 'Read More'); ?></a>
                                </div>
                            </div>
                        <?php $i++; endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Services Grid End -->
<?php endif; ?>

        <!-- CTA Start -->
        <div class="container-fluid py-5" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
            <div class="container py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8 wow fadeInLeft" data-wow-delay="0.2s">
                        <h2 class="text-white mb-3"><?php echo t('cta_title', 'Need a Weighing Scale Solution?'); ?></h2>
                        <p class="text-white mb-0 fs-5"><?php echo t('cta_desc', 'Contact us today for a free consultation and quote.'); ?></p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end wow fadeInRight" data-wow-delay="0.4s">
                        <a href="contact.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="btn btn-light py-3 px-5"><?php echo t('cta_btn', 'Get Free Quote'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- CTA End -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
