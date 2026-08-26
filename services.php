<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = t('nav_services', 'Services');
$show_breadcrumb = true;
$page_heading = t('nav_services', 'Our Services');
$breadcrumb_current = t('nav_services', 'Services');

// Fetch all services
$services_result = $conn->query("SELECT * FROM services WHERE status = 'active' ORDER BY sort_order ASC");

require_once __DIR__ . '/includes/header.php';
?>

        <!-- Services Start -->
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
                                    <a href="contact.php?service=<?php echo urlencode($service['title_en']); ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn btn-primary py-2 px-4"><?php echo t('services_readmore', 'Read More'); ?></a>
                                </div>
                            </div>
                        <?php $i++; endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Services End -->

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