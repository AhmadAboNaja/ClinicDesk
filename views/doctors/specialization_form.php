<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Add Specialization</h1>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <form method="POST" action="index.php?page=specializations&action=create">
            <div class="card-body">
                <?php echo CSRF::input(); ?>
                <div class="form-group">
                    <label for="name">Specialization Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn">Create</button>
                <a href="index.php?page=specializations" class="btn" style="background:#6c757d;">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
