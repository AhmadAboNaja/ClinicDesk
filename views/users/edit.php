<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Edit User</h1>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <div class="card-header">
            <h3><?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
        </div>
        <form method="POST" action="index.php?page=users&action=edit&id=<?php echo $user['id']; ?>">
            <div class="card-body">
                <?php echo CSRF::input(); ?>
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email (readonly)</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="form-group">
                    <label for="role">Role (readonly)</label>
                    <input type="text" value="<?php echo htmlspecialchars(strtoupper($user['role']), ENT_QUOTES, 'UTF-8'); ?>" readonly>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn">Save</button>
                <a href="index.php?page=users" class="btn" style="background:#6c757d;">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
