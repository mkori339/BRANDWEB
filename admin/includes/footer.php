<?php if (basename($_SERVER['PHP_SELF'], '.php') !== 'login'): ?>
        </div><!-- end .p-4 -->
    </div><!-- end .main-content -->
</div><!-- end .d-flex -->
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0 fs-5">Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger px-4"><i class="fas fa-trash me-1"></i> Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
var deleteModal;
document.addEventListener('DOMContentLoaded', function() {
    deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));

    // Old-style .btn-delete links (browser confirm fallback removed)
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('deleteConfirmBtn').href = btn.href;
            deleteModal.show();
        });
    });

    // New-style .btn-delete-item buttons
    document.querySelectorAll('.btn-delete-item').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteConfirmBtn').href = btn.dataset.url;
            deleteModal.show();
        });
    });

    // Sidebar toggle
    var sidebar = document.getElementById('adminSidebar');
    var overlay = document.getElementById('sidebarOverlay');
    var toggleBtn = document.getElementById('sidebarToggle');

    function toggleSidebar() {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('active');
    }

    if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', toggleSidebar);
});
</script>
<?php
// Flush output buffer at the end
if (ob_get_level() > 0) {
    ob_end_flush();
}
?>
</body>
</html>