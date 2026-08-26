<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = t('nav_home', 'Home');
$show_breadcrumb = false;

// Fetch featured services
$services_result = $conn->query("SELECT * FROM services WHERE status = 'active' ORDER BY sort_order ASC LIMIT 6");

// Fetch featured products
$products_result = $conn->query("SELECT * FROM products WHERE status = 'active' ORDER BY sort_order ASC LIMIT 4");

// Fetch recent blog posts
$blog_result = $conn->query("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");

// Fetch featured projects
$projects_result = $conn->query("SELECT * FROM projects WHERE status = 'active' AND is_featured = 1 ORDER BY sort_order ASC LIMIT 4");

// Fetch videos
$videos_result = $conn->query("SELECT * FROM media_videos WHERE status = 'active' ORDER BY sort_order ASC LIMIT 6");

// Fetch team members
$team_result = $conn->query("SELECT * FROM team_members WHERE status = 'active' ORDER BY sort_order ASC LIMIT 8");

require_once __DIR__ . '/includes/header.php';
?>

        <!-- Carousel Start -->
        <div class="header-carousel owl-carousel overflow-hidden">
            <div class="header-carousel-item hero-section">
                <div class="hero-bg-half-1"></div>
                <div class="hero-shape-1"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row g-4 align-items-center">
                            <div class="col-lg-7 animated fadeInLeft">
                                <div class="text-sm-center text-md-start">
                                    <h4 class="text-white text-uppercase fw-bold mb-4"><?php echo t('hero_subtitle', 'Professional Weighing Scale Solutions'); ?></h4>
                                    <h1 class="display-2 text-white mb-4"><?php echo t('hero_title', 'Your Trusted Partner in Weighing Solutions'); ?></h1>
                                    <p class="mb-5 fs-5"><?php echo t('hero_desc', 'We provide comprehensive weighing scale solutions including sales, manufacturing, calibration, maintenance and distribution across Tanzania.'); ?></p>
                                    <div class="d-flex justify-content-center justify-content-md-start flex-shrink-0 mb-4">
                                        <a class="btn btn-light py-3 px-4 px-md-5 me-2" href="services.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>"><i class="fas fa-cogs me-2"></i> <?php echo t('hero_btn_services', 'Our Services'); ?></a>
                                        <a class="btn btn-primary py-3 px-4 px-md-5 ms-2" href="contact.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>"><?php echo t('hero_btn_contact', 'Contact Us'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-carousel-item hero-section">
                <div class="hero-bg-half-2"></div>
                <div class="hero-shape-2"></div>
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row g-4 align-items-center">
                            <div class="col-lg-7 animated fadeInLeft">
                                <div class="text-sm-center text-md-start">
                                    <h4 class="text-white text-uppercase fw-bold mb-4"><?php echo t('services_tab_calibration', 'Calibration Services'); ?></h4>
                                    <h1 class="display-2 text-white mb-4"><?php echo t('about_title', 'Leading Weighing Scale Experts in Tanzania'); ?></h1>
                                    <p class="mb-5 fs-5"><?php echo t('about_desc', 'Ngalambela is a trusted name in Tanzania\'s weighing industry. With over 15 years of experience...'); ?></p>
                                    <div class="d-flex justify-content-center justify-content-md-start flex-shrink-0 mb-4">
                                        <a class="btn btn-light py-3 px-4 px-md-5 me-2" href="about.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>"><i class="fas fa-play-circle me-2"></i> <?php echo t('about_btn', 'Learn More'); ?></a>
                                        <a class="btn btn-primary py-3 px-4 px-md-5 ms-2" href="contact.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>"><?php echo t('nav_get_quote', 'Get a Quote'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel End -->


        <!-- About Start -->
        <div class="container-fluid about bg-light py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-7 wow fadeInLeft" data-wow-delay="0.2s">
                        <div class="h-100">
                            <h4 class="text-primary"><?php echo t('about_label', 'About Us'); ?></h4>
                            <h1 class="display-4 mb-4"><?php echo t('about_title', 'Leading Weighing Scale Experts in Tanzania'); ?></h1>
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <a href="#" class="d-flex">
                                        <span class="fas fa-balance-scale fa-3x me-3"></span>
                                        <h4 class="mb-0"><?php echo t('about_feature1_title', 'Scale Sales & Supply'); ?></h4>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="#" class="d-flex">
                                        <span class="fas fa-ruler fa-3x me-3"></span>
                                        <h4 class="mb-0"><?php echo t('about_feature2_title', 'Calibration Services'); ?></h4>
                                    </a>
                                </div>
                            </div>
                            <p class="mb-4"><?php echo t('about_desc', 'Ngalambela is a trusted name in Tanzania\'s weighing industry...'); ?></p>
                            <div class="text-dark mb-4">
                                <p class="fs-5"><span class="fa fa-check text-primary me-2"></span> <?php echo t('about_point1', 'Authorized dealer for major weighing scale brands'); ?></p>
                                <p class="fs-5"><span class="fa fa-check text-primary me-2"></span> <?php echo t('about_point2', 'ISO certified calibration and maintenance services'); ?></p>
                                <p class="fs-5"><span class="fa fa-check text-primary me-2"></span> <?php echo t('about_point3', 'Nationwide distribution network across Tanzania'); ?></p>
                            </div>
                            <a class="btn btn-primary py-3 px-4 px-md-5 ms-2" href="contact.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>"><?php echo t('hero_btn_contact', 'Contact Us'); ?></a>
                        </div>
                    </div>
                    <div class="col-lg-5 wow fadeInRight" data-wow-delay="0.2s">
                        <div class="position-relative h-100">
                            <img src="img/about-1.jpg" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="<?php echo $company_name; ?>">
                            <div class="bg-white">
                                <div class="position-absolute pt-3 bg-white" style="width: 50%; left: 0; bottom: 0;">
                                    <div class="bg-primary p-4">
                                        <h4 class="display-2 mb-0"><?php echo getSetting('company_experience', '15+'); ?></h4>
                                        <p class="text-white fs-5 mb-0"><?php echo t('about_experience_years', 'Years of Experience'); ?></p>
                                    </div>
                                </div>
                                <div class="position-absolute p-3 bg-white pb-0 pe-0" style="width: 50%; bottom: 0; right: 0;">
                                    <img src="img/about-2.jpg" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Services Start -->
        <div class="container-fluid service py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('services_label', 'Our Services'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('services_title', 'Comprehensive Weighing Solutions'); ?></h1>
                    <p class="mb-0"><?php echo t('services_desc', 'We offer end-to-end weighing scale solutions tailored to meet the needs of various industries.'); ?></p>
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
                                        <p class="mb-4"><?php echo htmlspecialchars($desc); ?></p>
                                        <a href="services.php?id=<?php echo $service['id']; ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn btn-primary py-2 px-4"><?php echo t('services_readmore', 'Read More'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Services End -->


        <!-- Products Start -->
        <div class="container-fluid bg-light py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('products_label', 'Our Products'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('products_title', 'Quality Weighing Equipment'); ?></h1>
                    <p class="mb-0"><?php echo t('products_desc', 'Browse our wide range of high-quality weighing scales and equipment for various applications.'); ?></p>
                </div>
                <div class="row g-4">
                    <?php if ($products_result && $products_result->num_rows > 0): ?>
                        <?php $i = 0; while ($product = $products_result->fetch_assoc()): ?>
                            <?php 
                            $name = $current_lang === 'sw' && !empty($product['name_sw']) ? $product['name_sw'] : $product['name_en'];
                            $pdesc = $current_lang === 'sw' && !empty($product['description_sw']) ? $product['description_sw'] : $product['description_en'];
                            ?>
                            <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="<?php echo (0.2 + $i * 0.2); ?>s">
                                <div class="product-item h-100">
                                    <div class="position-relative overflow-hidden">
                                        <?php if ($product['image'] && file_exists(__DIR__ . '/uploads/' . $product['image'])): ?>
                                            <img src="uploads/<?php echo $product['image']; ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($name); ?>">
                                        <?php else: ?>
                                            <img src="img/commercial-1.jpg" class="img-fluid w-100" alt="<?php echo htmlspecialchars($name); ?>">
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
                    <?php endif; ?>
                </div>
                <div class="text-center mt-4">
                    <a class="btn btn-primary py-3 px-5" href="products.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>"><?php echo t('products_view_all', 'View All Products'); ?></a>
                </div>
            </div>
        </div>
        <!-- Products End -->





        <!-- Blog Start -->
        <?php if ($blog_result && $blog_result->num_rows > 0): ?>
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('blog_label', 'Latest News'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('blog_title', 'Industry Insights & Updates'); ?></h1>
                    <p class="mb-0"><?php echo t('blog_desc', 'Stay informed with the latest news, articles and insights from the weighing industry.'); ?></p>
                </div>
                <div class="row g-4">
                    <?php $bi = 0; while ($post = $blog_result->fetch_assoc()): ?>
                        <?php 
                        $btitle = $current_lang === 'sw' && !empty($post['title_sw']) ? $post['title_sw'] : $post['title_en'];
                        $bexcerpt = $current_lang === 'sw' && !empty($post['excerpt_sw']) ? $post['excerpt_sw'] : $post['excerpt_en'];
                        ?>
                        <div class="col-md-4 wow fadeInUp" data-wow-delay="<?php echo (0.2 + $bi * 0.2); ?>s">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if ($post['featured_image'] && file_exists(__DIR__ . '/uploads/' . $post['featured_image'])): ?>
                                    <img src="uploads/<?php echo $post['featured_image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($btitle); ?>" style="object-fit: contain;">
                                <?php else: ?>
                                    <img src="img/blog-placeholder.svg" class="card-img-top" alt="<?php echo htmlspecialchars($btitle); ?>" style="object-fit: contain;">
                                <?php endif; ?>
                                <div class="card-body">
                                    <?php if ($post['category']): ?>
                                        <span class="bg-primary text-white px-2 py-1 rounded small mb-2 d-inline-block"><?php echo htmlspecialchars($post['category']); ?></span>
                                    <?php endif; ?>
                                    <h5 class="card-title"><?php echo htmlspecialchars($btitle); ?></h5>
                                    <p class="card-text text-muted"><?php echo htmlspecialchars(substr($bexcerpt, 0, 120)); ?>...</p>
                                </div>
                                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="fas fa-calendar me-1"></i> <?php echo date('M d, Y', strtotime($post['created_at'])); ?></small>
                                    <a href="blog.php?slug=<?php echo $post['slug']; ?><?php echo $current_lang !== 'en' ? '&lang=' . $current_lang : ''; ?>" class="btn btn-sm btn-primary"><?php echo t('blog_read_more', 'Read More'); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php $bi++; endwhile; ?>
                </div>
                <div class="text-center mt-4 wow fadeInUp" data-wow-delay="0.6s">
                    <a href="blog.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="btn btn-primary py-3 px-5"><?php echo t('blog_view_all', 'View All Posts'); ?></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Blog End -->

        <!-- Videos Section Start -->
        <?php if ($videos_result && $videos_result->num_rows > 0): ?>
        <div class="container-fluid bg-light py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('videos_label', 'Our Videos'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('videos_title', 'Watch Our Work'); ?></h1>
                    <p class="mb-0"><?php echo t('videos_desc', 'Check out videos of our projects, events and company activities.'); ?></p>
                </div>
                <div class="row g-4">
                    <?php $vi = 0; while ($video = $videos_result->fetch_assoc()): ?>
                        <?php 
                        $vtitle = $current_lang === 'sw' && !empty($video['title_sw']) ? $video['title_sw'] : $video['title_en'];
                        $vdesc = $current_lang === 'sw' && !empty($video['description_sw']) ? $video['description_sw'] : $video['description_en'];
                        ?>
                        <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="<?php echo (0.2 + $vi * 0.2); ?>s">
                            <div class="card h-100 border-0 shadow-sm video-card">
                                <div class="video-thumb-wrapper position-relative overflow-hidden cursor-pointer" onclick="openVideoModal('<?php echo $video['youtube_id']; ?>')">
                                    <img src="https://img.youtube.com/vi/<?php echo $video['youtube_id']; ?>/mqdefault.jpg" class="card-img-top" alt="<?php echo htmlspecialchars($vtitle); ?>" loading="lazy">
                                    <div class="video-play-overlay">
                                        <div class="video-play-btn">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($vtitle); ?></h5>
                                    <?php if ($vdesc): ?>
                                        <p class="card-text text-muted small"><?php echo htmlspecialchars($vdesc); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php $vi++; endwhile; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Videos Section End -->

        <!-- Team Start -->
        <?php if ($team_result && $team_result->num_rows > 0): ?>
        <div class="container-fluid team py-5">
            <div class="container py-5">
                <div class="d-flex flex-column mx-auto text-center mb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-primary"><?php echo t('team_label', 'Our Team'); ?></h4>
                    <h1 class="display-4 mb-4"><?php echo t('team_title', 'Meet Our Experts'); ?></h1>
                    <p class="mb-0"><?php echo t('team_desc', 'Our experienced team is dedicated to providing the best weighing solutions.'); ?></p>
                </div>
                <div class="row g-4">
                    <?php $ti = 0; while ($member = $team_result->fetch_assoc()): ?>
                        <?php
                        $mname = $current_lang === 'sw' && !empty($member['name_sw']) ? $member['name_sw'] : $member['name_en'];
                        $mposition = $current_lang === 'sw' && !empty($member['position_sw']) ? $member['position_sw'] : $member['position_en'];
                        $mdept = $current_lang === 'sw' && !empty($member['department_sw']) ? $member['department_sw'] : $member['department_en'];
                        ?>
                        <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="<?php echo (0.2 + $ti * 0.2); ?>s">
                            <div class="team-item h-100">
                                <div class="team-img">
                                    <?php if ($member['photo'] && file_exists(__DIR__ . '/uploads/' . $member['photo'])): ?>
                                        <img src="uploads/<?php echo $member['photo']; ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($mname); ?>" style="object-fit: cover; height: 280px;">
                                    <?php else: ?>
                                        <img src="img/team-1.jpg" class="img-fluid w-100" alt="<?php echo htmlspecialchars($mname); ?>" style="object-fit: cover; height: 280px;">
                                    <?php endif; ?>
                                    <div class="team-icon">
                                        <?php if (!empty($member['facebook_url'])): ?><a class="btn btn-square btn-primary mb-2" href="<?php echo htmlspecialchars($member['facebook_url']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                                        <?php if (!empty($member['linkedin_url'])): ?><a class="btn btn-square btn-primary mb-2" href="<?php echo htmlspecialchars($member['linkedin_url']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                                        <?php if (!empty($member['twitter_url'])): ?><a class="btn btn-square btn-primary mb-2" href="<?php echo htmlspecialchars($member['twitter_url']); ?>" target="_blank"><i class="fab fa-twitter"></i></a><?php endif; ?>
                                    </div>
                                </div>
                                <div class="team-content bg-light text-center p-4">
                                    <h4 class="mb-1"><?php echo htmlspecialchars($mname); ?></h4>
                                    <p class="mb-0"><?php echo htmlspecialchars($mposition); ?><?php if ($mdept) echo ' &middot; ' . htmlspecialchars($mdept); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php $ti++; endwhile; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Team End -->

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