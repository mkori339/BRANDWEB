<?php
$admin_page_title = 'My Profile';
require_once __DIR__ . '/includes/header.php';

$user = $admin_user;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    
    $stmt = $conn->prepare("UPDATE admins SET full_name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $full_name, $email, $_SESSION['admin_id']);
    $stmt->execute();
    
    // Update password if provided
    if (!empty($_POST['new_password'])) {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'];
        
        if (password_verify($current, $user['password'])) {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $conn->query("UPDATE admins SET password = '$hashed' WHERE id = " . $_SESSION['admin_id']);
            $message = '<div class="alert alert-success">Profile updated successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger">Current password is incorrect.</div>';
        }
    } else {
        $message = '<div class="alert alert-success">Profile updated successfully.</div>';
    }
    
    // Refresh user data
    $stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
}
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card form-card">
            <div class="card-header"><h5 class="mb-0"><i class="fas fa-user-cog me-2"></i> My Profile</h5></div>
            <div class="card-body">
                <?php echo $message; ?>
                <form method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                            <small class="text-muted">Username cannot be changed.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role</label>
                            <input type="text" class="form-control" value="<?php echo ucfirst($user['role']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="col-12">
                            <hr>
                            <h6 class="fw-bold mb-3">Change Password (leave blank to keep current)</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Current Password</label>
                            <input type="password" class="form-control" name="current_password">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">New Password</label>
                            <input type="password" class="form-control" name="new_password">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" oninput="if(this.value !== document.getElementsByName('new_password')[0].value) this.setCustomValidity('Passwords do not match'); else this.setCustomValidity('');">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-1"></i> Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>