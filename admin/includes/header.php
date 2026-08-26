<?php
// Start output buffering to prevent accidental output before redirects
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../lang/init.php';

// Check if admin is logged in (except login page)
$current_admin_page = basename($_SERVER['PHP_SELF'], '.php');
if ($current_admin_page !== 'login' && $current_admin_page !== 'install' && !isLoggedIn()) {
    redirect(ADMIN_URL . '/login.php');
}

$admin_user = null;
if (isLoggedIn()) {
    $stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    $admin_user = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { min-height: 100vh; background: #1a1c23; width: 250px; position: fixed; top: 0; left: 0; z-index: 100; transition: all 0.3s; }
        .sidebar .nav-link { color: #8b8d97; padding: 12px 20px; display: flex; align-items: center; border-radius: 8px; margin: 2px 10px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(13, 110, 253, 0.15); }
        .sidebar .nav-link i { width: 20px; margin-right: 10px; }
        .sidebar-brand { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-brand h4 { color: #fff; margin: 0; font-size: 18px; }
        .sidebar-brand small { color: #8b8d97; }
        .main-content { margin-left: 250px; background: #f5f6fa; min-height: 100vh; }
        .top-navbar { background: #fff; padding: 15px 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .table-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .table-card .card-header { background: #fff; border-bottom: 1px solid #eee; padding: 15px 20px; }
        .form-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .form-card .card-header { background: #fff; border-bottom: 1px solid #eee; padding: 15px 20px; }
        .btn-action { padding: 4px 8px; font-size: 12px; }
        @media (max-width: 768px) { .sidebar { width: 0; overflow: hidden; } .main-content { margin-left: 0; } }
    </style>
</head>
<body>
<?php if ($current_admin_page !== 'login' && $current_admin_page !== 'install'): ?>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-none d-md-block">
        <div class="sidebar-brand">
            <h4><i class="fas fa-balance-scale text-primary me-2"></i><?php echo SITE_NAME; ?></h4>
            <small>Admin Dashboard</small>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link <?php echo $current_admin_page === 'index' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'blog' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/blog.php">
                <i class="fas fa-newspaper"></i> Blog Posts
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'products' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/products.php">
                <i class="fas fa-box"></i> Products
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'services' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/services.php">
                <i class="fas fa-cogs"></i> Services
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'projects' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/projects.php">
                <i class="fas fa-project-diagram"></i> Projects
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'team' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/team.php">
                <i class="fas fa-users-cog"></i> Team Members
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'testimonials' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/testimonials.php">
                <i class="fas fa-star"></i> Testimonials
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'videos' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/videos.php">
                <i class="fas fa-video"></i> Videos
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'inquiries' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/inquiries.php">
                <i class="fas fa-envelope"></i> Inquiries
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'subscribers' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/subscribers.php">
                <i class="fas fa-users"></i> Subscribers
            </a>
            <hr class="text-secondary mx-3">
            <a class="nav-link <?php echo $current_admin_page === 'settings' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/settings.php">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a class="nav-link <?php echo $current_admin_page === 'pages' ? 'active' : ''; ?>" href="<?php echo ADMIN_URL; ?>/pages.php">
                <i class="fas fa-file-alt"></i> Page Content
            </a>
            <a class="nav-link" href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
            <hr class="text-secondary mx-3">
            <a class="nav-link text-danger" href="<?php echo ADMIN_URL; ?>/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><?php echo isset($admin_page_title) ? $admin_page_title : 'Dashboard'; ?></h5>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="d-none d-md-inline"><?php echo $admin_user['full_name'] ?? 'Admin'; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo ADMIN_URL; ?>/profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo ADMIN_URL; ?>/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="p-4">
            <?php
            $flash = getFlash();
            if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $flash['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
<?php endif; ?>