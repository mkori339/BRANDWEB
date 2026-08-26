<?php
/**
 * Common Header Include
 * Contains topbar, navbar, and page header/breadcrumb
 */

// Get current page for active nav
$current_page = basename($_SERVER['PHP_SELF'], '.php');
if ($current_page === 'index') $current_page = 'home';

// Get logo from settings
$site_logo = getSetting('logo', '');
$company_name = getSettingByLang('company_name', $current_lang, 'Ngalambela');
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">

    <head>
        <meta charset="utf-8">
        <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo $company_name; ?> - <?php echo t('hero_subtitle', 'Professional Weighing Solutions'); ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="weighing scales, calibration, maintenance, manufacturing, Tanzania, Mwanza, Ngalambela, vioo vya uzito" name="keywords">
        <meta content="<?php echo t('hero_desc', 'Professional weighing scale solutions in Tanzania'); ?>" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Jost:ital,wght@0,300;0,400;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link rel="stylesheet" href="lib/animate/animate.min.css"/>
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css?v=<?php echo filemtime(__DIR__ . '/../css/style.css'); ?>" rel="stylesheet">
    </head>

    <body>

        <!-- Navbar & Hero Start -->
        <div class="container-fluid header-top">
            <div class="container d-flex align-items-center">
                <div class="d-flex align-items-center h-100">
                    <a href="index.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="navbar-brand" style="height: 125px;">
                        <?php if ($site_logo): ?>
                            <img src="uploads/<?php echo $site_logo; ?>" alt="<?php echo $company_name; ?>" style="max-height: 80px;">
                        <?php else: ?>
                            <h1 class="text-primary mb-0"><i class="fas fa-balance-scale"></i> <?php echo $company_name; ?></h1>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="w-100 h-100">
                    <div class="topbar px-0 py-2 d-none d-lg-block" style="height: 45px;">
                        <div class="row gx-0 align-items-center">
                            <div class="col-lg-8 text-center text-lg-center mb-lg-0">
                                <div class="d-flex flex-wrap">
                                    <div class="border-end border-primary pe-3">
                                        <a href="#" class="text-muted small"><i class="fas fa-map-marker-alt text-primary me-2"></i><?php echo t('topbar_location', 'Buhongwa Nyanembe, Mwanza'); ?></a>
                                    </div>
                                    <div class="ps-3">
                                        <a href="mailto:<?php echo getSetting('contact_email', 'info@ngalambela.co.tz'); ?>" class="text-muted small"><i class="fas fa-envelope text-primary me-2"></i><?php echo getSetting('contact_email', 'info@ngalambela.co.tz'); ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center text-lg-end">
                                <div class="d-flex justify-content-end">
                                    <div class="d-flex border-end border-primary pe-3">
                                        <a class="btn p-0 text-primary me-3" href="<?php echo getSetting('social_facebook', '#'); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                        <a class="btn p-0 text-primary me-3" href="<?php echo getSetting('social_twitter', '#'); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                        <a class="btn p-0 text-primary me-3" href="<?php echo getSetting('social_instagram', '#'); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                        <?php if (getSetting('social_whatsapp', '')): ?>
                                        <a class="btn p-0 text-primary me-0" href="https://wa.me/<?php echo getSetting('social_whatsapp', ''); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="dropdown ms-3">
                                        <a href="#" class="dropdown-toggle text-white" data-bs-toggle="dropdown">
                                            <small class="text-body"><i class="fas fa-globe text-primary me-2"></i> <?php echo $current_lang === 'en' ? 'English' : 'Kiswahili'; ?></small>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a href="?lang=en" class="dropdown-item <?php echo $current_lang === 'en' ? 'active' : ''; ?>">English</a>
                                            <a href="?lang=sw" class="dropdown-item <?php echo $current_lang === 'sw' ? 'active' : ''; ?>">Kiswahili</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nav-bar px-0 py-lg-0" style="height: 80px;">
                        <nav class="navbar navbar-expand-lg navbar-light d-flex justify-content-lg-end">
                            <a href="index.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="navbar-brand-2">
                                <?php if ($site_logo): ?>
                                    <img src="uploads/<?php echo $site_logo; ?>" alt="<?php echo $company_name; ?>" style="max-height: 50px;">
                                <?php else: ?>
                                    <h1 class="text-primary mb-0"><i class="fas fa-balance-scale"></i> <?php echo $company_name; ?></h1>
                                <?php endif; ?>
                            </a>  
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                                <span class="fa fa-bars"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarCollapse">
                                <div class="navbar-nav mx-0 mx-lg-auto bg-white">
                                    <a href="index.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="nav-item nav-link <?php echo $current_page === 'home' ? 'active' : ''; ?>"><?php echo t('nav_home', 'Home'); ?></a>
                                    <a href="about.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="nav-item nav-link <?php echo $current_page === 'about' ? 'active' : ''; ?>"><?php echo t('nav_about', 'About'); ?></a>
                                    <a href="services.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="nav-item nav-link <?php echo $current_page === 'services' ? 'active' : ''; ?>"><?php echo t('nav_services', 'Services'); ?></a>
                                    <a href="products.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="nav-item nav-link <?php echo $current_page === 'products' ? 'active' : ''; ?>"><?php echo t('nav_products', 'Products'); ?></a>
                                    <a href="projects.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="nav-item nav-link <?php echo $current_page === 'projects' ? 'active' : ''; ?>"><?php echo t('nav_projects', 'Projects'); ?></a>
                                    <a href="blog.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="nav-item nav-link <?php echo $current_page === 'blog' ? 'active' : ''; ?>"><?php echo t('nav_blog', 'Blog'); ?></a>
                                    <a href="testimonials.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="nav-item nav-link <?php echo $current_page === 'testimonials' ? 'active' : ''; ?>"><?php echo t('nav_testimonials', 'Testimonials'); ?></a>
                                    <a href="contact.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="nav-item nav-link <?php echo $current_page === 'contact' ? 'active' : ''; ?>"><?php echo t('nav_contact', 'Contact Us'); ?></a>
                                    <div class="nav-btn ps-3 d-none d-lg-block">
                                        <a href="contact.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>" class="btn btn-primary py-2 px-4 ms-0 ms-lg-3"><?php echo t('nav_get_quote', 'Get a Quote'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->

<?php
// Page Header/Breadcrumb (used on inner pages)
if (isset($show_breadcrumb) && $show_breadcrumb) {
?>
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s"><?php echo isset($page_heading) ? $page_heading : ''; ?></h4>
                <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="index.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>"><?php echo t('nav_home', 'Home'); ?></a></li>
                    <?php if (isset($breadcrumb_parent)): ?>
                    <li class="breadcrumb-item"><a href="#"><?php echo $breadcrumb_parent; ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active text-primary"><?php echo isset($breadcrumb_current) ? $breadcrumb_current : ''; ?></li>
                </ol>    
            </div>
        </div>
        <!-- Header End -->
<?php } ?>
