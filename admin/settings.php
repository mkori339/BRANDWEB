<?php
$admin_page_title = 'Website Settings';
require_once __DIR__ . '/includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = $_POST['settings'] ?? [];
    
    foreach ($settings as $key => $value) {
        $value = sanitize($value);
        $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->bind_param("sss", $key, $value, $value);
        $stmt->execute();
    }
    
    // Handle file uploads
    $file_fields = ['logo', 'favicon', 'banner_image'];
    foreach ($file_fields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico'];
            $filename = $field . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '', basename($_FILES[$field]['name']));
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $upload_dir = __DIR__ . '/../uploads/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $upload_dir . $filename)) {
                    $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                    $stmt->bind_param("sss", $field, $filename, $filename);
                    $stmt->execute();
                }
            }
        }
    }
    
    setFlash('success', 'Settings saved successfully.');
    redirect(ADMIN_URL . '/settings.php');
}

// Get all settings
$all_settings = [];
$result = $conn->query("SELECT * FROM settings ORDER BY setting_group, setting_key");
while ($row = $result->fetch_assoc()) {
    $all_settings[$row['setting_key']] = $row;
}
?>

<form method="POST" enctype="multipart/form-data">
    <ul class="nav nav-tabs mb-4" id="settingsTabs">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#company">Company Info</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#contact">Contact Info</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#social">Social Media</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#homepage">Homepage Content</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#media">Logo & Images</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#footer">Footer</a></li>
    </ul>

    <div class="tab-content">
        <!-- Company Info -->
        <div class="tab-pane fade show active" id="company">
            <div class="card form-card"><div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6"><label class="form-label fw-semibold">Company Name (English)</label><input type="text" class="form-control" name="settings[company_name]" value="<?php echo htmlspecialchars($all_settings['company_name']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Company Name (Kiswahili)</label><input type="text" class="form-control" name="settings[company_name_sw]" value="<?php echo htmlspecialchars($all_settings['company_name_sw']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Tagline (English)</label><input type="text" class="form-control" name="settings[company_tagline]" value="<?php echo htmlspecialchars($all_settings['company_tagline']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Tagline (Kiswahili)</label><input type="text" class="form-control" name="settings[company_tagline_sw]" value="<?php echo htmlspecialchars($all_settings['company_tagline_sw']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Description (English)</label><textarea class="form-control" name="settings[company_description]" rows="4"><?php echo htmlspecialchars($all_settings['company_description']['setting_value'] ?? ''); ?></textarea></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Description (Kiswahili)</label><textarea class="form-control" name="settings[company_description_sw]" rows="4"><?php echo htmlspecialchars($all_settings['company_description_sw']['setting_value'] ?? ''); ?></textarea></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Years of Experience</label><input type="text" class="form-control" name="settings[company_experience]" value="<?php echo htmlspecialchars($all_settings['company_experience']['setting_value'] ?? ''); ?>"></div>
                </div>
            </div></div>
        </div>

        <!-- Contact Info -->
        <div class="tab-pane fade" id="contact">
            <div class="card form-card"><div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" class="form-control" name="settings[contact_email]" value="<?php echo htmlspecialchars($all_settings['contact_email']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Phone 1</label><input type="text" class="form-control" name="settings[contact_phone]" value="<?php echo htmlspecialchars($all_settings['contact_phone']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Phone 2</label><input type="text" class="form-control" name="settings[contact_phone2]" value="<?php echo htmlspecialchars($all_settings['contact_phone2']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Address (English)</label><input type="text" class="form-control" name="settings[contact_address]" value="<?php echo htmlspecialchars($all_settings['contact_address']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Address (Kiswahili)</label><input type="text" class="form-control" name="settings[contact_address_sw]" value="<?php echo htmlspecialchars($all_settings['contact_address_sw']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Map Latitude</label><input type="text" class="form-control" name="settings[contact_map_lat]" value="<?php echo htmlspecialchars($all_settings['contact_map_lat']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Map Longitude</label><input type="text" class="form-control" name="settings[contact_map_lng]" value="<?php echo htmlspecialchars($all_settings['contact_map_lng']['setting_value'] ?? ''); ?>"></div>
                </div>
            </div></div>
        </div>

        <!-- Social Media -->
        <div class="tab-pane fade" id="social">
            <div class="card form-card"><div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6"><label class="form-label fw-semibold">Facebook URL</label><input type="url" class="form-control" name="settings[social_facebook]" value="<?php echo htmlspecialchars($all_settings['social_facebook']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Twitter URL</label><input type="url" class="form-control" name="settings[social_twitter]" value="<?php echo htmlspecialchars($all_settings['social_twitter']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Instagram URL</label><input type="url" class="form-control" name="settings[social_instagram]" value="<?php echo htmlspecialchars($all_settings['social_instagram']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">WhatsApp Number (with country code)</label><input type="text" class="form-control" name="settings[social_whatsapp]" value="<?php echo htmlspecialchars($all_settings['social_whatsapp']['setting_value'] ?? ''); ?>" placeholder="+255754123456"></div>
                </div>
            </div></div>
        </div>

        <!-- Homepage Content -->
        <div class="tab-pane fade" id="homepage">
            <div class="card form-card"><div class="card-body">
                <h5 class="mb-3">Hero Section</h5>
                <div class="row g-4 mb-4">
                    <div class="col-md-6"><label class="form-label fw-semibold">Hero Title (English)</label><input type="text" class="form-control" name="settings[hero_title_en]" value="<?php echo htmlspecialchars($all_settings['hero_title_en']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Hero Title (Kiswahili)</label><input type="text" class="form-control" name="settings[hero_title_sw]" value="<?php echo htmlspecialchars($all_settings['hero_title_sw']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Hero Subtitle (English)</label><textarea class="form-control" name="settings[hero_subtitle_en]" rows="3"><?php echo htmlspecialchars($all_settings['hero_subtitle_en']['setting_value'] ?? ''); ?></textarea></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Hero Subtitle (Kiswahili)</label><textarea class="form-control" name="settings[hero_subtitle_sw]" rows="3"><?php echo htmlspecialchars($all_settings['hero_subtitle_sw']['setting_value'] ?? ''); ?></textarea></div>
                </div>
                <h5 class="mb-3">About Section</h5>
                <div class="row g-4 mb-4">
                    <div class="col-md-6"><label class="form-label fw-semibold">About Title (English)</label><input type="text" class="form-control" name="settings[about_title_en]" value="<?php echo htmlspecialchars($all_settings['about_title_en']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">About Title (Kiswahili)</label><input type="text" class="form-control" name="settings[about_title_sw]" value="<?php echo htmlspecialchars($all_settings['about_title_sw']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">About Content (English)</label><textarea class="form-control" name="settings[about_content_en]" rows="4"><?php echo htmlspecialchars($all_settings['about_content_en']['setting_value'] ?? ''); ?></textarea></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">About Content (Kiswahili)</label><textarea class="form-control" name="settings[about_content_sw]" rows="4"><?php echo htmlspecialchars($all_settings['about_content_sw']['setting_value'] ?? ''); ?></textarea></div>
                </div>
                <h5 class="mb-3">Section Titles</h5>
                <div class="row g-4">
                    <div class="col-md-6"><label class="form-label fw-semibold">Services Title (EN)</label><input type="text" class="form-control" name="settings[services_title_en]" value="<?php echo htmlspecialchars($all_settings['services_title_en']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Services Title (SW)</label><input type="text" class="form-control" name="settings[services_title_sw]" value="<?php echo htmlspecialchars($all_settings['services_title_sw']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Products Title (EN)</label><input type="text" class="form-control" name="settings[products_title_en]" value="<?php echo htmlspecialchars($all_settings['products_title_en']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Products Title (SW)</label><input type="text" class="form-control" name="settings[products_title_sw]" value="<?php echo htmlspecialchars($all_settings['products_title_sw']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Testimonials Title (EN)</label><input type="text" class="form-control" name="settings[testimonials_title_en]" value="<?php echo htmlspecialchars($all_settings['testimonials_title_en']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Testimonials Title (SW)</label><input type="text" class="form-control" name="settings[testimonials_title_sw]" value="<?php echo htmlspecialchars($all_settings['testimonials_title_sw']['setting_value'] ?? ''); ?>"></div>
                </div>
            </div></div>
        </div>

        <!-- Logo & Images -->
        <div class="tab-pane fade" id="media">
            <div class="card form-card"><div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4"><label class="form-label fw-semibold">Site Logo</label><input type="file" class="form-control" name="logo" accept="image/*"><?php if (!empty($all_settings['logo']['setting_value'])): ?><div class="mt-2"><img src="../uploads/<?php echo $all_settings['logo']['setting_value']; ?>" style="max-height: 80px;"></div><?php endif; ?></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Favicon</label><input type="file" class="form-control" name="favicon" accept="image/*"><?php if (!empty($all_settings['favicon']['setting_value'])): ?><div class="mt-2"><img src="../uploads/<?php echo $all_settings['favicon']['setting_value']; ?>" style="height: 32px;"></div><?php endif; ?></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Banner Image</label><input type="file" class="form-control" name="banner_image" accept="image/*"><?php if (!empty($all_settings['banner_image']['setting_value'])): ?><div class="mt-2"><img src="../uploads/<?php echo $all_settings['banner_image']['setting_value']; ?>" style="max-height: 100px;"></div><?php endif; ?></div>
                </div>
            </div></div>
        </div>

        <!-- Footer -->
        <div class="tab-pane fade" id="footer">
            <div class="card form-card"><div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6"><label class="form-label fw-semibold">Footer Text (English)</label><input type="text" class="form-control" name="settings[footer_text]" value="<?php echo htmlspecialchars($all_settings['footer_text']['setting_value'] ?? ''); ?>"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Footer Text (Kiswahili)</label><input type="text" class="form-control" name="settings[footer_text_sw]" value="<?php echo htmlspecialchars($all_settings['footer_text_sw']['setting_value'] ?? ''); ?>"></div>
                </div>
            </div></div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fas fa-save me-2"></i> Save All Settings</button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>