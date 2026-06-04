<?php
require_once __DIR__ . '/../../core/CSRF.php';
// This would normally fetch doctors from the database
?>
<div class="content-header">
    <div class="container-fluid">
        <h1>Book an Appointment</h1>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <form action="index.php?page=book_appointment_submit" method="POST">
                <?php echo CSRF::input(); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="doctor_id">Select Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="form-control" required>
                            <option value="">-- Choose Doctor --</option>
                            <!-- Doctor options will be loaded here -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="appt_date">Preferred Date</label>
                        <input type="date" name="appt_date" id="appt_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="appt_time">Time Slot</label>
                        <select name="appt_time" id="appt_time" class="form-control" required>
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
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Visit</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>
