<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Appointment Reports</h1>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <div class="card-header">
            <h3>Generate Report</h3>
        </div>
        <form method="POST" action="index.php?page=reports">
            <div class="card-body">
                <?php echo CSRF::input(); ?>
                <div class="form-group">
                    <label for="start_date">Start Date *</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date *</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div>
                <div class="form-group">
                    <label for="doctor_id">Doctor (Optional)</label>
                    <select id="doctor_id" name="doctor_id">
                        <option value="">-- All Doctors --</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?php echo $doctor['user_id']; ?>"><?php echo htmlspecialchars($doctor['user_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status (Optional)</label>
                    <select id="status" name="status">
                        <option value="">-- All --</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn">Generate Report</button>
                <button type="submit" name="export" value="csv" class="btn" style="background:#28a745;">Export CSV</button>
            </div>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
