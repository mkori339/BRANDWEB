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
        html, body { min-height: 100%; max-width: 100%; overflow-x: clip; }
        html { scroll-padding-top: var(--admin-topbar-height, 60px); }
        * { font-family: 'Inter', sans-serif; }
        .admin-shell { width: 100%; min-width: 0; min-height: 100vh; min-height: 100dvh; }
        .sidebar { height: 100vh; height: 100dvh; height: var(--admin-viewport-height, 100dvh); max-height: var(--admin-viewport-height, 100dvh); background: #1a1c23; width: 260px; position: fixed; top: 0; left: 0; z-index: 1050; transition: transform 0.3s ease; overflow-y: auto; overflow-x: hidden; overscroll-behavior: contain; padding-top: env(safe-area-inset-top); padding-bottom: env(safe-area-inset-bottom); }
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
        .sidebar .nav-link { color: #8b8d97; padding: 11px 20px; display: flex; align-items: center; border-radius: 8px; margin: 2px 10px; transition: all 0.2s; font-size: 14px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(13, 110, 253, 0.15); }
        .sidebar .nav-link i { width: 20px; margin-right: 10px; text-align: center; }
        .sidebar-brand { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); position: sticky; top: 0; background: #1a1c23; z-index: 1; }
        .sidebar-brand h4 { color: #fff; margin: 0; font-size: 18px; }
        .sidebar-brand small { color: #8b8d97; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; height: 100vh; height: 100dvh; height: var(--admin-viewport-height, 100dvh); background: rgba(0,0,0,0.5); z-index: 1049; }
        .sidebar-overlay.active { display: block; }
        .main-content { margin-left: 260px; padding-top: 60px; padding-top: var(--admin-topbar-height, 60px); background: #f5f6fa; min-height: 100vh; min-height: 100dvh; min-width: 0; width: 0; flex: 1 1 0%; transition: margin-left 0.3s ease; }
        .main-content > .p-4 { min-width: 0; width: 100%; max-width: 100%; }
        body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
        body.sidebar-collapsed .main-content { margin-left: 0; }
        .top-navbar { background: #fff; padding: 12px 25px; padding: calc(12px + env(safe-area-inset-top)) 25px 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); position: fixed; top: 0; right: 0; left: 260px; z-index: 1048; }
        body.sidebar-collapsed .top-navbar { left: 0; }
        .top-navbar > .d-flex { min-width: 0; }
        .top-navbar h5 { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        body.admin-sidebar-open { overflow: hidden; }
        .sidebar-toggle { background: none; border: none; font-size: 20px; color: #333; cursor: pointer; padding: 6px 10px; border-radius: 6px; transition: background 0.2s; }
        .sidebar-toggle:hover { background: #f0f0f0; }
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s; background: #fff; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 15px rgba(0,0,0,0.12); }
        .stat-card .card-body { padding: 20px; display: flex; align-items: center; }
        .stat-card .stat-icon {
            width: 56px; height: 56px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; flex-shrink: 0;
        }
        .stat-card .stat-icon i { line-height: 1; }
        .stat-card h3 { font-size: 28px; font-weight: 700; margin-bottom: 0 !important; }
        .stat-card small { font-size: 13px; }
        .table-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); background: #fff; }
        .table-card .card-header { background: #fff; border-bottom: 1px solid #eee; padding: 15px 20px; }
        .form-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); background: #fff; }
        .form-card .card-header { background: #fff; border-bottom: 1px solid #eee; padding: 15px 20px; }
        .btn-action { padding: 4px 8px; font-size: 12px; }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); width: 280px; }
            .sidebar.show { transform: translateX(0) !important; }
            body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0 !important; padding-top: 60px; padding-top: var(--admin-topbar-height, 60px); width: 100%; }
            .top-navbar, body.sidebar-collapsed .top-navbar { left: 0; }
            .top-navbar { padding: 12px 15px; padding: calc(12px + env(safe-area-inset-top)) 15px 12px; }
        }
        @media (max-width: 575.98px) {
            .sidebar { width: 85vw; max-width: 300px; }
            .main-content > .p-4 { padding: 12px !important; }
            .top-navbar { padding-left: 10px; padding-right: 10px; }
            .top-navbar h5 { font-size: 1rem; }
            .main-content > .p-4 .row { --bs-gutter-x: .75rem; --bs-gutter-y: .75rem; }
            .main-content > .p-4 .d-flex.justify-content-between { flex-wrap: wrap; gap: 8px; margin-bottom: 16px !important; }
            .main-content > .p-4 .d-flex.justify-content-between > .btn { font-size: .8rem; padding: 6px 9px; }
            .stat-card { border-radius: 8px; }
            .stat-card .card-body { padding: 10px; }
            .stat-card .stat-icon { width: 38px; height: 38px; font-size: 16px; }
            .stat-card .me-3 { margin-right: 8px !important; }
            .stat-card h3 { font-size: 20px; }
            .stat-card small { font-size: 11px; line-height: 1.2; }
            .table-card, .form-card { border-radius: 8px; }
            .table-card .card-header, .form-card .card-header { padding: 10px 12px; }
            .table-card .card-header h5, .form-card .card-header h5 { font-size: 1rem; }
            .table { font-size: .75rem; }
            .table > :not(caption) > * > * { padding: 6px 7px; white-space: nowrap; }
            .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .form-card .card-body { padding: 12px; }
            .form-label { font-size: .8rem; margin-bottom: 4px; }
            .form-control, .form-select { font-size: .85rem; padding: 6px 8px; }
            .btn { font-size: .85rem; }
            .btn-action { padding: 3px 6px; font-size: 11px; }
            .modal-dialog { width: auto; max-width: calc(100% - 16px); margin: 8px auto; }
            .modal-content { max-height: calc(100vh - 16px); max-height: calc(100dvh - 16px); overflow-y: auto; }
            .modal-header, .modal-body, .modal-footer { padding: 12px; }
            .modal-title { font-size: 1rem; }
        }
    </style>
</head>
<body>
<?php if ($current_admin_page !== 'login' && $current_admin_page !== 'install'): ?>
<div class="d-flex admin-shell">
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="adminSidebar">
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
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle me-2" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
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
