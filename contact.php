<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lang/init.php';

$page_title = t('nav_contact', 'Contact Us');
$show_breadcrumb = true;
$page_heading = t('contact_title', 'Get In Touch');
$breadcrumb_current = t('nav_contact', 'Contact Us');

// Handle form submission
$form_success = '';
$form_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $form_error = t('contact_form_error', 'Please fill in all required fields.');
    } else {
        $stmt = $conn->prepare("INSERT INTO inquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
        if ($stmt->execute()) {
            $form_success = t('contact_form_success', 'Your message has been sent successfully!');
        } else {
            $form_error = t('contact_form_error', 'There was an error. Please try again.');
        }
    }
}

$map_lat = getSetting('contact_map_lat', '-2.5164');
$map_lng = getSetting('contact_map_lng', '32.9175');

require_once __DIR__ . '/includes/header.php';
?>

        <!-- Contact Start -->
        <div class="container-fluid contact bg-light py-5">
            <div class="container py-5">
                <?php if ($form_success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $form_success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($form_error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $form_error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="row g-5">
                    <div class="col-lg-5 wow fadeInLeft" data-wow-delay="0.2s">
                        <div>
                            <h4 class="text-primary"><?php echo t('contact_label', 'Contact Us'); ?></h4>
                            <h1 class="display-5 mb-5"><?php echo t('contact_title', 'Get In Touch'); ?></h1>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; min-width: 55px;">
                                    <i class="fa fa-map-marker-alt text-white"></i>
                                </div>
                                <div class="ms-4">
                                    <h5><?php echo t('contact_address', 'Our Location'); ?></h5>
                                    <p class="mb-0"><?php echo getSettingByLang('contact_address', $current_lang, 'Buhongwa Nyanembe, Mwanza, Tanzania'); ?></p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; min-width: 55px;">
                                    <i class="fa fa-phone text-white"></i>
                                </div>
                                <div class="ms-4">
                                    <h5><?php echo t('contact_phone', 'Call Us'); ?></h5>
                                    <p class="mb-1"><a href="tel:<?php echo getSetting('contact_phone', '+255 754 123 456'); ?>"><?php echo getSetting('contact_phone', '+255 754 123 456'); ?></a></p>
                                    <?php if (getSetting('contact_phone2', '')): ?>
                                        <p class="mb-0"><a href="tel:<?php echo getSetting('contact_phone2', ''); ?>"><?php echo getSetting('contact_phone2', ''); ?></a></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; min-width: 55px;">
                                    <i class="fa fa-envelope text-white"></i>
                                </div>
                                <div class="ms-4">
                                    <h5><?php echo t('contact_email', 'Email Us'); ?></h5>
                                    <p class="mb-0"><a href="mailto:<?php echo getSetting('contact_email', 'info@ngalambela.co.tz'); ?>"><?php echo getSetting('contact_email', 'info@ngalambela.co.tz'); ?></a></p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; min-width: 55px;">
                                    <i class="fa fa-clock text-white"></i>
                                </div>
                                <div class="ms-4">
                                    <h5><?php echo t('contact_hours', 'Working Hours'); ?></h5>
                                    <p class="mb-0"><?php echo t('contact_hours_time', 'Mon - Sat: 8:00 AM - 6:00 PM'); ?></p>
                                </div>
                            </div>
                            
                            <!-- Map -->
                            <div class="h-100 overflow-hidden rounded">
                                <iframe class="w-100" style="height: 250px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.5!2d<?php echo $map_lng; ?>!3d<?php echo $map_lat; ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sBuhongwa+Nyanembe%2C+Mwanza!5e0!3m2!1sen!2stz!4v1234567890!5m2!1sen!2stz" 
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 wow fadeInRight" data-wow-delay="0.4s">
                        <div>
                            <h4 class="lh-base mb-4"><?php echo t('contact_form_title', 'Send Us a Message'); ?></h4>
                            <form method="POST" action="contact.php<?php echo $current_lang !== 'en' ? '?lang=' . $current_lang : ''; ?>">
                                <input type="hidden" name="contact_form" value="1">
                                <div class="row g-4">
                                    <div class="col-lg-12 col-xl-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="name" name="name" placeholder="<?php echo t('contact_form_name', 'Your Name'); ?>" required>
                                            <label for="name"><?php echo t('contact_form_name', 'Your Name'); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control border-0" id="email" name="email" placeholder="<?php echo t('contact_form_email', 'Your Email'); ?>" required>
                                            <label for="email"><?php echo t('contact_form_email', 'Your Email'); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-6">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control border-0" id="phone" name="phone" placeholder="<?php echo t('contact_form_phone', 'Your Phone'); ?>">
                                            <label for="phone"><?php echo t('contact_form_phone', 'Your Phone'); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="subject" name="subject" placeholder="<?php echo t('contact_form_subject', 'Subject'); ?>">
                                            <label for="subject"><?php echo t('contact_form_subject', 'Subject'); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control border-0" placeholder="<?php echo t('contact_form_message', 'Your Message'); ?>" id="message" name="message" style="height: 125px" required></textarea>
                                            <label for="message"><?php echo t('contact_form_message', 'Your Message'); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit"><i class="fas fa-paper-plane me-2"></i> <?php echo t('contact_form_submit', 'Send Message'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact End -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>