<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Create User</h1>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <div class="card-header">
            <h3>New User</h3>
        </div>
        <form method="POST" action="index.php?page=users&action=create">
            <div class="card-body">
                <?php echo CSRF::input(); ?>
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="temp_password">Temporary Password *</label>
                    <input type="password" id="temp_password" name="temp_password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="patient">Patient</option>
                        <option value="doctor">Doctor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn">Create</button>
                <a href="index.php?page=users" class="btn" style="background:#6c757d;">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
