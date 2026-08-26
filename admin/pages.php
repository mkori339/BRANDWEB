<?php
$admin_page_title = 'Page Content';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = $_POST['settings'] ?? [];
    foreach ($settings as $key => $value) {
        $value = sanitize($value);
        $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->bind_param("sss", $key, $value, $value);
        $stmt->execute();
    }
    setFlash('success', 'Page content updated successfully.');
    redirect(ADMIN_URL . '/pages.php');
}

$all_settings = [];
$result = $conn->query("SELECT * FROM settings WHERE setting_group = 'homepage' ORDER BY setting_key");
while ($row = $result->fetch_assoc()) {
    $all_settings[$row['setting_key']] = $row;
}
?>

<h4 class="mb-4">Manage Page Content</h4>
<p class="text-muted">Update the text content displayed on the website. Both English and Kiswahili translations are supported.</p>

<form method="POST">
    <!-- Hero Section -->
    <div class="card form-card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fas fa-home me-2"></i> Hero / Banner Section</h5></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6"><label class="form-label fw-semibold">Hero Title (English)</label><input type="text" class="form-control" name="settings[hero_title_en]" value="<?php echo htmlspecialchars($all_settings['hero_title_en']['setting_value'] ?? ''); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Hero Title (Kiswahili)</label><input type="text" class="form-control" name="settings[hero_title_sw]" value="<?php echo htmlspecialchars($all_settings['hero_title_sw']['setting_value'] ?? ''); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Hero Subtitle (English)</label><textarea class="form-control" name="settings[hero_subtitle_en]" rows="3"><?php echo htmlspecialchars($all_settings['hero_subtitle_en']['setting_value'] ?? ''); ?></textarea></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Hero Subtitle (Kiswahili)</label><textarea class="form-control" name="settings[hero_subtitle_sw]" rows="3"><?php echo htmlspecialchars($all_settings['hero_subtitle_sw']['setting_value'] ?? ''); ?></textarea></div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="card form-card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> About Section</h5></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6"><label class="form-label fw-semibold">About Title (English)</label><input type="text" class="form-control" name="settings[about_title_en]" value="<?php echo htmlspecialchars($all_settings['about_title_en']['setting_value'] ?? ''); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">About Title (Kiswahili)</label><input type="text" class="form-control" name="settings[about_title_sw]" value="<?php echo htmlspecialchars($all_settings['about_title_sw']['setting_value'] ?? ''); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">About Content (English)</label><textarea class="form-control" name="settings[about_content_en]" rows="5"><?php echo htmlspecialchars($all_settings['about_content_en']['setting_value'] ?? ''); ?></textarea></div>
                <div class="col-md-6"><label class="form-label fw-semibold">About Content (Kiswahili)</label><textarea class="form-control" name="settings[about_content_sw]" rows="5"><?php echo htmlspecialchars($all_settings['about_content_sw']['setting_value'] ?? ''); ?></textarea></div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div class="card form-card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fas fa-cogs me-2"></i> Services Section</h5></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6"><label class="form-label fw-semibold">Services Title (English)</label><input type="text" class="form-control" name="settings[services_title_en]" value="<?php echo htmlspecialchars($all_settings['services_title_en']['setting_value'] ?? ''); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Services Title (Kiswahili)</label><input type="text" class="form-control" name="settings[services_title_sw]" value="<?php echo htmlspecialchars($all_settings['services_title_sw']['setting_value'] ?? ''); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Services Subtitle (English)</label><textarea class="form-control" name="settings[services_subtitle_en]" rows="2"><?php echo htmlspecialchars($all_settings['services_subtitle_en']['setting_value'] ?? ''); ?></textarea></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Services Subtitle (Kiswahili)</label><textarea class="form-control" name="settings[services_subtitle_sw]" rows="2"><?php echo htmlspecialchars($all_settings['services_subtitle_sw']['setting_value'] ?? ''); ?></textarea></div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="card form-card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fas fa-box me-2"></i> Products Section</h5></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6"><label class="form-label fw-semibold">Products Title (English)</label><input type="text" class="form-control" name="settings[products_title_en]" value="<?php echo htmlspecialchars($all_settings['products_title_en']['setting_value'] ?? ''); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Products Title (Kiswahili)</label><input type="text" class="form-control" name="settings[products_title_sw]" value="<?php echo htmlspecialchars($all_settings['products_title_sw']['setting_value'] ?? ''); ?>"></div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="card form-card mb-4">
        <div class="card-header"><h5 class="mb-0"><i class="fas fa-star me-2"></i> Testimonials Section</h5></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6"><label class="form-label fw-semibold">Testimonials Title (English)</label><input type="text" class="form-control" name="settings[testimonials_title_en]" value="<?php echo htmlspecialchars($all_settings['testimonials_title_en']['setting_value'] ?? ''); ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Testimonials Title (Kiswahili)</label><input type="text" class="form-control" name="settings[testimonials_title_sw]" value="<?php echo htmlspecialchars($all_settings['testimonials_title_sw']['setting_value'] ?? ''); ?>"></div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fas fa-save me-2"></i> Save All Changes</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>