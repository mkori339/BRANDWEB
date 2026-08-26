<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = t('nav_about', 'About Us');
$show_breadcrumb = true;
$page_heading = t('nav_about', 'About Us');
$breadcrumb_current = t('nav_about', 'About Us');

// Fetch team members
$team_result = $conn->query("SELECT * FROM team_members WHERE status = 'active' ORDER BY sort_order ASC");

require_once __DIR__ . '/includes/header.php';
?>

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
                            <p class="mb-4"><?php echo t('about_desc', 'Ngalambela is a trusted name in Tanzania\'s weighing industry. With over 15 years of experience, we provide comprehensive weighing solutions to businesses across the country.'); ?></p>
                            <p class="mb-4"><?php echo t('company_description', 'Ngalambela is a leading company in Tanzania specializing in the sale, manufacturing, calibration, maintenance, and distribution of weighing scales across Tanzania.'); ?></p>
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

        <!-- Mission & Vision Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="p-4 bg-light rounded h-100">
                            <div class="mb-3">
                                <i class="fas fa-bullseye fa-3x text-primary"></i>
                            </div>
                            <h3 class="mb-3"><?php echo $current_lang === 'sw' ? 'Dhamira Yetu' : 'Our Mission'; ?></h3>
                            <p class="mb-0"><?php echo $current_lang === 'sw' 
                                ? 'Kutoa suluhisho bora zaidi za kupima uzito kwa kuendesha ubora, uvumbuzi, na huduma za wateja katika sekta ya uzito nchini Tanzania.' 
                                : 'To provide the best weighing scale solutions by driving quality, innovation, and customer service in Tanzania\'s weighing industry.'; ?></p>
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="p-4 bg-light rounded h-100">
                            <div class="mb-3">
                                <i class="fas fa-eye fa-3x text-primary"></i>
                            </div>
                            <h3 class="mb-3"><?php echo $current_lang === 'sw' ? 'Maono Yetu' : 'Our Vision'; ?></h3>
                            <p class="mb-0"><?php echo $current_lang === 'sw' 
                                ? 'Kuwa kampuni inayoongoza na inayoaminika zaidi ya vioo vya uzito katika Afrika Mashariki, tunazingatia teknolojia ya hali ya juu na ufanisi.' 
                                : 'To be the most trusted and leading weighing scale company in East Africa, embracing cutting-edge technology and efficiency.'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mission & Vision End -->

        <!-- Stats Start -->
        <div class="container-fluid py-5" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
            <div class="container py-5">
                <div class="row g-4 text-center">
                    <div class="col-md-3 col-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="text-white">
                            <h2 class="display-3 mb-0"><?php echo getSetting('company_experience', '15+'); ?></h2>
                            <p class="fs-5 mb-0"><?php echo t('about_experience_years', 'Years of Experience'); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="text-white">
                            <h2 class="display-3 mb-0">500+</h2>
                            <p class="fs-5 mb-0"><?php echo t('about_clients', 'Satisfied Clients'); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="text-white">
                            <h2 class="display-3 mb-0">1000+</h2>
                            <p class="fs-5 mb-0"><?php echo $current_lang === 'sw' ? 'Miradi Ilivyokamilika' : 'Projects Completed'; ?></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="text-white">
                            <h2 class="display-3 mb-0">30+</h2>
                            <p class="fs-5 mb-0"><?php echo $current_lang === 'sw' ? 'Mikoa Inayofikiwa' : 'Regions Covered'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats End -->

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

<?php require_once __DIR__ . '/includes/footer.php'; ?>