<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Book Appointment</h1>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <form method="POST" action="index.php?page=appointments&action=book">
            <div class="card-body">
                <?php echo CSRF::input(); ?>
                <div class="form-group">
                    <label for="doctor_id">Doctor *</label>
                    <select id="doctor_id" name="doctor_id" required>
                        <option value="">-- Select Doctor --</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?php echo $doctor['user_id']; ?>"><?php echo htmlspecialchars($doctor['user_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="appt_date">Preferred Date *</label>
                    <input type="date" id="appt_date" name="appt_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="appt_time">Time Slot *</label>
                    <select id="appt_time" name="appt_time" required>
                        <option value="">-- Select Time --</option>
                        <option value="09:00:00">09:00 AM</option>
                        <option value="09:30:00">09:30 AM</option>
                        <option value="10:00:00">10:00 AM</option>
                        <option value="10:30:00">10:30 AM</option>
                        <option value="11:00:00">11:00 AM</option>
                        <option value="11:30:00">11:30 AM</option>
                        <option value="14:00:00">02:00 PM</option>
                        <option value="14:30:00">02:30 PM</option>
                        <option value="15:00:00">03:00 PM</option>
                        <option value="15:30:00">03:30 PM</option>
                        <option value="16:00:00">04:00 PM</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reason">Reason for Visit</label>
                    <textarea id="reason" name="reason" rows="3"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn">Book Appointment</button>
                <a href="index.php?page=dashboard" class="btn" style="background:#6c757d;">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
