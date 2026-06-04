<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Create Doctor</h1>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <div class="card-header">
            <h3>New Doctor Record</h3>
        </div>
        <form method="POST" action="index.php?page=doctors&action=create">
            <div class="card-body">
                <?php echo CSRF::input(); ?>
                <div class="form-group">
                    <label for="user_id">User *</label>
                    <input type="number" id="user_id" name="user_id" required>
                </div>
                <div class="form-group">
                    <label for="specialization_id">Specialization *</label>
                    <select id="specialization_id" name="specialization_id" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($specializations as $spec): ?>
                            <option value="<?php echo $spec['id']; ?>"><?php echo htmlspecialchars($spec['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="consultation_fee">Consultation Fee</label>
                    <input type="number" id="consultation_fee" name="consultation_fee" step="0.01" value="0">
                </div>
                <div class="form-group">
                    <label>Available Days</label>
                    <div>
                        <?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day): ?>
                            <label><input type="checkbox" name="available_days[]" value="<?php echo $day; ?>"> <?php echo $day; ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn">Create</button>
                <a href="index.php?page=doctors" class="btn" style="background:#6c757d;">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
