<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = t('nav_testimonials', 'Testimonials');
$show_breadcrumb = true;
$page_heading = t('nav_testimonials', 'Testimonials');
$breadcrumb_current = t('nav_testimonials', 'Testimonials');

$testimonials_result = $conn->query("SELECT * FROM testimonials WHERE status = 'active' ORDER BY sort_order ASC");

require_once __DIR__ . '/includes/header.php';
?>

        <!-- Testimonials Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('testimonials_label', 'Testimonials'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('testimonials_title', 'What Our Clients Say'); ?></h1>
                    <p class="mb-0"><?php echo t('testimonials_desc', 'Hear from our satisfied clients about their experience working with Ngalambela.'); ?></p>
                </div>

                <div class="row g-4">
                    <?php if ($testimonials_result && $testimonials_result->num_rows > 0): ?>
                        <?php $i = 0; while ($testimonial = $testimonials_result->fetch_assoc()): ?>
                            <?php 
                            $content = $current_lang === 'sw' && !empty($testimonial['content_sw']) ? $testimonial['content_sw'] : $testimonial['content_en'];
                            $delay = 0.2 + ($i % 3) * 0.1;
                            ?>
                            <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                <div class="card h-100 border-0 shadow-sm p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-quote-left fa-2x text-primary"></i>
                                    </div>
                                    <p class="mb-4"><?php echo htmlspecialchars($content); ?></p>
                                    <div class="d-flex text-primary mb-3">
                                        <?php for ($s = 0; $s < $testimonial['rating']; $s++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                        <?php for ($s = $testimonial['rating']; $s < 5; $s++): ?>
                                            <i class="far fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <?php if ($testimonial['photo']): ?>
                                            <img src="uploads/<?php echo $testimonial['photo']; ?>" class="rounded-circle me-3" alt="<?php echo htmlspecialchars($testimonial['client_name']); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($testimonial['client_name']); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($testimonial['client_title'] . ($testimonial['company'] ? ' - ' . $testimonial['company'] : '')); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p class="fs-5 text-muted"><?php echo $current_lang === 'sw' ? 'Hakuna maoni yaliyopatikana.' : 'No testimonials found.'; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Testimonials End -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>