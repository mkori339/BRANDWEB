<?php
$admin_page_title = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

// Get stats
$blog_count = $conn->query("SELECT COUNT(*) as c FROM blog_posts")->fetch_assoc()['c'];
$products_count = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$projects_count = $conn->query("SELECT COUNT(*) as c FROM projects")->fetch_assoc()['c'];
$testimonials_count = $conn->query("SELECT COUNT(*) as c FROM testimonials")->fetch_assoc()['c'];
$inquiries_count = $conn->query("SELECT COUNT(*) as c FROM inquiries")->fetch_assoc()['c'];
$unread_inquiries = $conn->query("SELECT COUNT(*) as c FROM inquiries WHERE is_read = 0")->fetch_assoc()['c'];
$subscribers_count = $conn->query("SELECT COUNT(*) as c FROM subscribers WHERE status = 'active'")->fetch_assoc()['c'];
$videos_count = $conn->query("SELECT COUNT(*) as c FROM media_videos")->fetch_assoc()['c'];
$team_count = $conn->query("SELECT COUNT(*) as c FROM team_members")->fetch_assoc()['c'];

// Recent inquiries
$recent_inquiries = $conn->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5");
?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $blog_count; ?></h3>
                    <small class="text-muted">Blog Posts</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $products_count; ?></h3>
                    <small class="text-muted">Products</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $projects_count; ?></h3>
                    <small class="text-muted">Projects</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $inquiries_count; ?></h3>
                    <small class="text-muted">Inquiries <span class="badge bg-danger"><?php echo $unread_inquiries; ?> new</span></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger me-3">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $testimonials_count; ?></h3>
                    <small class="text-muted">Testimonials</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary me-3">
                    <i class="fas fa-video"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $videos_count; ?></h3>
                    <small class="text-muted">Videos</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $subscribers_count; ?></h3>
                    <small class="text-muted">Subscribers</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-dark bg-opacity-10 text-dark me-3">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo $team_count; ?></h3>
                    <small class="text-muted">Team Members</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Inquiries -->
<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Inquiries</h5>
        <a href="<?php echo ADMIN_URL; ?>/inquiries.php" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recent_inquiries && $recent_inquiries->num_rows > 0): ?>
                        <?php while ($inquiry = $recent_inquiries->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($inquiry['name']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['subject'] ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?></td>
                                <td>
                                    <?php if ($inquiry['is_read']): ?>
                                        <span class="badge bg-success">Read</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">New</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No inquiries yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>