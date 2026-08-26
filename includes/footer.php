<?php
/**
 * Common Footer Include
 */
$lang_code = $current_lang !== 'en' ? '?lang=' . $current_lang : '';
?>
        <!-- Footer Start -->
        <div class="container-fluid footer bg-dark py-5 wow fadeIn" data-wow-delay="0.2s">
            <div class="container py-5">
                <div class="row g-5 mb-5 align-items-center">
                    <div class="col-lg-7">
                        <div class="position-relative mx-auto">
                            <form method="POST" action="api/subscribe.php" class="d-flex">
                                <input class="form-control w-100 py-3 ps-4 pe-5" type="email" name="email" placeholder="<?php echo t('footer_subscribe_placeholder', 'Enter your email'); ?>" required>
                                <button type="submit" class="btn btn-primary position-absolute top-0 end-0 py-2 px-4 mt-2 me-2"><?php echo t('footer_subscribe_btn', 'Subscribe'); ?></button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-end">
                            <?php if (getSetting('social_facebook', '#') !== '#'): ?>
                            <a class="btn btn-light btn-md-square me-3" href="<?php echo getSetting('social_facebook', '#'); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            <?php if (getSetting('social_twitter', '#') !== '#'): ?>
                            <a class="btn btn-light btn-md-square me-3" href="<?php echo getSetting('social_twitter', '#'); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            <?php if (getSetting('social_instagram', '#') !== '#'): ?>
                            <a class="btn btn-light btn-md-square me-3" href="<?php echo getSetting('social_instagram', '#'); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            <?php if (getSetting('social_whatsapp', '')): ?>
                            <a class="btn btn-light btn-md-square me-0" href="https://wa.me/<?php echo getSetting('social_whatsapp', ''); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="footer-item d-flex flex-column">
                            <div class="footer-item">
                                <h3 class="text-white mb-4"><i class="fas fa-balance-scale text-primary me-3"></i><?php echo $company_name; ?></h3>
                                <p class="mb-3"><?php echo t('footer_about', 'Ngalambela is a leading company in Tanzania specializing in weighing scale solutions.'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4"><?php echo t('footer_contact', 'Contact Info'); ?></h4>
                            <a href="#"><i class="fa fa-map-marker-alt text-primary me-2"></i> <?php echo t('topbar_location', 'Buhongwa Nyanembe, Mwanza, Tanzania'); ?></a>
                            <a href="mailto:<?php echo getSetting('contact_email', 'info@ngalambela.co.tz'); ?>"><i class="fas fa-envelope text-primary me-2"></i> <?php echo getSetting('contact_email', 'info@ngalambela.co.tz'); ?></a>
                            <a href="tel:<?php echo getSetting('contact_phone', '+255 754 123 456'); ?>"><i class="fas fa-phone text-primary me-2"></i> <?php echo getSetting('contact_phone', '+255 754 123 456'); ?></a>
                            <?php if (getSetting('contact_phone2', '')): ?>
                            <a href="tel:<?php echo getSetting('contact_phone2', ''); ?>" class="mb-3"><i class="fas fa-phone text-primary me-2"></i> <?php echo getSetting('contact_phone2', ''); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Copyright Start -->
        <div class="container-fluid copyright py-4">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4 text-center text-md-start mb-md-0">
                        <span class="text-body">&copy; <?php echo date('Y'); ?> <?php echo $company_name; ?>. <?php echo t('footer_rights', 'All Rights Reserved.'); ?></span>
                    </div>
                    <div class="col-md-4 text-center text-body">
                        <a href="https://portifolio-psi-fawn-94.vercel.app/" target="_blank" class="text-muted small text-decoration-none">Created by <strong class="text-white">Mkori Tech</strong></a>
                    </div>
                    <div class="col-md-4 text-center text-md-end">
                        <a href="admin/login.php" class="text-muted small text-decoration-none"><i class="fas fa-lock me-1"></i>Admin Login</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

        <!-- Video Modal -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-dark border-0 rounded-4 overflow-hidden">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="ratio ratio-16x9">
                            <iframe id="videoModalIframe" src="" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Video Modal End -->

        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/wow/wow.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        
        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>

</html>