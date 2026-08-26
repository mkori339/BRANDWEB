<?php
/**
 * Ngalambela Database Installation Script
 * Run this once to set up the database tables
 */

require_once __DIR__ . '/database.php';

// Check if admin already exists - prevent re-running
$check_admin = $conn->query("SELECT id FROM admins LIMIT 1");
if ($check_admin && $check_admin->num_rows > 0) {
    die("Installation already completed. Admin user exists. Delete this file or remove the admin from database to re-run.");

// Create tables
$sql = "

-- Admin users table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Website settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'image', 'boolean', 'number') DEFAULT 'text',
    setting_group VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Blog posts table
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255) NOT NULL,
    title_sw VARCHAR(255),
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt_en TEXT,
    excerpt_sw TEXT,
    content_en LONGTEXT,
    content_sw LONGTEXT,
    featured_image VARCHAR(500),
    category VARCHAR(100),
    status ENUM('draft', 'published') DEFAULT 'draft',
    views INT DEFAULT 0,
    author_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(255) NOT NULL,
    name_sw VARCHAR(255),
    slug VARCHAR(255) NOT NULL UNIQUE,
    description_en TEXT,
    description_sw TEXT,
    features_en TEXT,
    features_sw TEXT,
    image VARCHAR(500),
    category VARCHAR(100),
    price VARCHAR(100),
    is_featured TINYINT(1) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Projects/Portfolio table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255) NOT NULL,
    title_sw VARCHAR(255),
    slug VARCHAR(255) NOT NULL UNIQUE,
    description_en TEXT,
    description_sw TEXT,
    client_en VARCHAR(255),
    client_sw VARCHAR(255),
    location VARCHAR(255),
    completion_date VARCHAR(100),
    image VARCHAR(500),
    gallery TEXT,
    category VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    is_featured TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(150) NOT NULL,
    client_title VARCHAR(150),
    company VARCHAR(150),
    content_en TEXT,
    content_sw TEXT,
    rating INT DEFAULT 5,
    photo VARCHAR(500),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contact inquiries table
CREATE TABLE IF NOT EXISTS inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(255),
    message TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Media/Videos table (YouTube links)
CREATE TABLE IF NOT EXISTS media_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255),
    title_sw VARCHAR(255),
    description_en TEXT,
    description_sw TEXT,
    youtube_url VARCHAR(500) NOT NULL,
    youtube_id VARCHAR(50),
    thumbnail VARCHAR(500),
    category VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Services table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255) NOT NULL,
    title_sw VARCHAR(255),
    slug VARCHAR(255) NOT NULL UNIQUE,
    description_en TEXT,
    description_sw TEXT,
    icon VARCHAR(100),
    image VARCHAR(500),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Team members table
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(150) NOT NULL,
    name_sw VARCHAR(150),
    position_en VARCHAR(150) NOT NULL,
    position_sw VARCHAR(150),
    department_en VARCHAR(150),
    department_sw VARCHAR(150),
    bio_en TEXT,
    bio_sw TEXT,
    photo VARCHAR(500),
    facebook_url VARCHAR(500),
    linkedin_url VARCHAR(500),
    twitter_url VARCHAR(500),
    email VARCHAR(150),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Newsletter subscribers
CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
";

// Execute each statement separately
$statements = array_filter(array_map('trim', explode(';', $sql)));

foreach ($statements as $statement) {
    if (!empty($statement)) {
        if (!$conn->query($statement)) {
            echo "Error: " . $conn->error . "\n";
        }
    }
}

// Insert default admin user (password: Mkori339@.)
$hashed_password = password_hash('Mkori339@.', PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT IGNORE INTO admins (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
$username = 'admin';
$email = 'admin@ngalambela.co.tz';
$full_name = 'Site Administrator';
$role = 'admin';
$stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $role);
$stmt->execute();

// Insert default settings
$default_settings = [
    // Company Info
    ['site_name', 'Ngalambela', 'text', 'general'],
    ['company_name', 'Ngalambela', 'text', 'company'],
    ['company_name_sw', 'Ngalambela', 'text', 'company'],
    ['company_tagline', 'Professional Weighing Solutions', 'text', 'company'],
    ['company_tagline_sw', 'Suluhisho za Utoaji Mizani', 'text', 'company'],
    ['company_description', 'Ngalambela is a leading company in Tanzania specializing in the sale, manufacturing, calibration, maintenance, and distribution of weighing scales across Tanzania.', 'textarea', 'company'],
    ['company_description_sw', 'Ngalambela ni kampuni inayoongoza nchini Tanzania inayojikita katika mauzo, utengenezaji, ukadiriaji, matengenezo, na usambazaji wa vioo vya kupima uzito Tanzania nzima.', 'textarea', 'company'],
    ['company_experience', '15+', 'text', 'company'],
    
    // Contact Info
    ['contact_email', 'info@ngalambela.co.tz', 'text', 'contact'],
    ['contact_phone', '+255 754 123 456', 'text', 'contact'],
    ['contact_phone2', '+255 785 654 321', 'text', 'contact'],
    ['contact_address', 'Buhongwa Nyanembe, Mwanza, Tanzania', 'text', 'contact'],
    ['contact_address_sw', 'Buhongwa Nyanembe, Mwanza, Tanzania', 'text', 'contact'],
    ['contact_map_lat', '-2.5164', 'text', 'contact'],
    ['contact_map_lng', '32.9175', 'text', 'contact'],
    
    // Social Media
    ['social_facebook', '#', 'text', 'social'],
    ['social_twitter', '#', 'text', 'social'],
    ['social_instagram', '#', 'text', 'social'],
    ['social_whatsapp', '+255754123456', 'text', 'social'],
    
    // Homepage Content
    ['hero_title_en', 'Professional Weighing Scale Solutions in Tanzania', 'text', 'homepage'],
    ['hero_title_sw', 'Suluhisho za Kitaalamu za Vioo vya Uzito nchini Tanzania', 'text', 'homepage'],
    ['hero_subtitle_en', 'Your trusted partner for weighing scale sales, manufacturing, calibration, maintenance and distribution', 'textarea', 'homepage'],
    ['hero_subtitle_sw', 'Mwenzi wako wa kuaminika kwa mauzo, utengenezaji, ukadiriaji, matengenezo na usambazaji wa vioo vya uzito', 'textarea', 'homepage'],
    ['about_title_en', 'About Ngalambela', 'text', 'homepage'],
    ['about_title_sw', 'Kuhusu Ngalambela', 'text', 'homepage'],
    ['about_content_en', 'Ngalambela is a trusted name in Tanzania\'s weighing industry. With years of experience, we provide comprehensive weighing solutions including sales, manufacturing, calibration, maintenance and distribution of all types of weighing scales.', 'textarea', 'homepage'],
    ['about_content_sw', 'Ngalambela ni jina linaloaminika katika sekta ya uzito ya Tanzania. Kwa miaka mingi ya uzoefu, tunatoa suluhisho kamili za uzito ikiwa ni pamoja na mauzo, utengenezaji, ukadiriaji, matengenezo na usambazaji wa aina zote za vioo vya uzito.', 'textarea', 'homepage'],
    ['services_title_en', 'Our Services', 'text', 'homepage'],
    ['services_title_sw', 'Huduma Zetu', 'text', 'homepage'],
    ['services_subtitle_en', 'Comprehensive weighing scale solutions for every industry need', 'textarea', 'homepage'],
    ['services_subtitle_sw', 'Suluhisho kamili za vioo vya uzito kwa kila hitaji la viwanda', 'textarea', 'homepage'],
    ['products_title_en', 'Our Products', 'text', 'homepage'],
    ['products_title_sw', 'Bidhaa Zetu', 'text', 'homepage'],
    ['testimonials_title_en', 'What Our Clients Say', 'text', 'homepage'],
    ['testimonials_title_sw', 'Wateja Wetu Wanasema Nini', 'text', 'homepage'],
    
    // Footer
    ['footer_text', 'Ngalambela. All rights reserved.', 'text', 'footer'],
    ['footer_text_sw', 'Ngalambela. Haki zote zimehifadhiwa.', 'text', 'footer'],
    
    // Logo & Images
    ['logo', '', 'image', 'media'],
    ['favicon', '', 'image', 'media'],
    ['banner_image', '', 'image', 'media'],
];

$stmt = $conn->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, setting_type, setting_group) VALUES (?, ?, ?, ?)");
foreach ($default_settings as $setting) {
    $stmt->bind_param("ssss", $setting[0], $setting[1], $setting[2], $setting[3]);
    $stmt->execute();
}

// Insert sample services
$sample_services = [
    ['Weighing Scale Sales', 'Mauzo ya Vioo vya Uzito', 'We offer a wide range of high-quality weighing scales for commercial, industrial, and laboratory use.', 'Tunatoa aina mbalimbali za vioo vya uzito vya ubora wa juu kwa matumizi ya biashara, viwanda, na maabara.', 'fas fa-balance-scale', 'commercial-1.jpg', 1],
    ['Scale Manufacturing', 'Utengenezaji wa Vioo', 'Custom manufacturing of weighing scales designed to meet your specific requirements and industry standards.', 'Utengenezaji maalum wa vioo vya uzito uliobuniwa kukidhi mahitaji yako maalum na viwango vya viwanda.', 'fas fa-cogs', 'industrial-1.jpg', 2],
    ['Calibration Services', 'Huduma za Ukadiriaji', 'Professional calibration services to ensure your weighing equipment maintains accuracy and compliance.', 'Huduma za ukadiriaji za kitaalamu ili kuhakikisha vifaa vyako vya uzito vinadumisha usahihi na kufuata viwango.', 'fas fa-ruler', 'commercial-2.jpg', 3],
    ['Maintenance & Repair', 'Matengenezo na Ukarabati', 'Expert maintenance and repair services to keep your weighing scales in optimal working condition.', 'Huduma za matengenezo na ukarabati za wataalamu ili kudumisha vioo vyako katika hali nzuri ya kufanya kazi.', 'fas fa-tools', 'industrial-2.jpg', 4],
    ['Distribution', 'Usambazaji', 'Nationwide distribution of weighing scales and related equipment across Tanzania.', 'Usambazaji wa vioo vya uzito na vifaa vinavyohusiana Tanzania nzima.', 'fas fa-truck', 'commercial-3.jpg', 5],
    ['Consultation', 'Ushauri', 'Expert consultation on weighing solutions, equipment selection, and industry compliance requirements.', 'Ushauri wa wataalamu kuhusu suluhisho za uzito, uteuzi wa vifaa, na mahitaji ya kufuata viwango vya sekta.', 'fas fa-comments', 'industrial-3.jpg', 6],
];

$stmt = $conn->prepare("INSERT IGNORE INTO services (title_en, title_sw, description_en, description_sw, icon, image, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($sample_services as $service) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $service[0]), '-'));
    // Check if slug exists
    $check = $conn->prepare("SELECT id FROM services WHERE slug = ?");
    $check->bind_param("s", $slug);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        $stmt->bind_param("ssssssi", $service[0], $service[1], $service[2], $service[3], $service[4], $service[5], $service[6]);
        $stmt->execute();
    }
}

echo "Database installation completed successfully!\n";
echo "Admin login: admin@ngalambela.co.tz / Mkori339@.\n";
echo "You can now access the admin panel at: " . ADMIN_URL . "\n";
}
?>