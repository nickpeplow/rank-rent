<aside id="secondary" class="widget-area">
    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <div class="card mb-4">
            <div class="card-body">
                <?php dynamic_sidebar('sidebar-1'); ?>
            </div>
        </div>
    <?php else : ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Sidebar</h5>
                <p class="card-text">This sidebar is empty. Add some widgets to it in the WordPress admin area.</p>
            </div>
        </div>
    <?php endif; ?>
</aside>