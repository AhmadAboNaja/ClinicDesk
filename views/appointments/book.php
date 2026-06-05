<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-calendar-plus mr-2"></i>Book Appointment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=appointments">Appointments</a></li>
                    <li class="breadcrumb-item active">Book</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php displayFlash(); ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Appointment Details</h3>
                    </div>
                    <form method="POST" action="index.php?page=appointments&action=book">
                        <div class="card-body">
                            <?php echo CSRF::input(); ?>
                            <div class="form-group">
                                <label for="doctor_id">Select Doctor <span class="text-danger">*</span></label>
                                <select class="form-control" id="doctor_id" name="doctor_id" required>
                                    <option value="">-- Select Doctor --</option>
                                    <?php foreach ($doctors as $doctor): ?>
                                        <option value="<?php echo $doctor['user_id']; ?>">
                                            <?php echo htmlspecialchars($doctor['user_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                                            <?php if (!empty($doctor['specialization_name'])): ?>
                                                - <?php echo htmlspecialchars($doctor['specialization_name'], ENT_QUOTES, 'UTF-8'); ?>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="appt_date">Preferred Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="appt_date" name="appt_date" required min="<?php echo date('Y-m-d'); ?>">
                                <small class="form-text text-muted">Select a date from today onwards.</small>
                            </div>
                            <div class="form-group">
                                <label for="appt_time">Time Slot <span class="text-danger">*</span></label>
                                <select class="form-control" id="appt_time" name="appt_time" required>
                                    <option value="">-- Select Time --</option>
                                    <optgroup label="Morning">
                                        <option value="09:00:00">09:00 AM</option>
                                        <option value="09:30:00">09:30 AM</option>
                                        <option value="10:00:00">10:00 AM</option>
                                        <option value="10:30:00">10:30 AM</option>
                                        <option value="11:00:00">11:00 AM</option>
                                        <option value="11:30:00">11:30 AM</option>
                                    </optgroup>
                                    <optgroup label="Afternoon">
                                        <option value="14:00:00">02:00 PM</option>
                                        <option value="14:30:00">02:30 PM</option>
                                        <option value="15:00:00">03:00 PM</option>
                                        <option value="15:30:00">03:30 PM</option>
                                        <option value="16:00:00">04:00 PM</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="reason">Reason for Visit</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Describe your reason for the appointment..."></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check mr-1"></i> Book Appointment
                            </button>
                            <a href="index.php?page=appointments" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>Important Information</h3>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>Appointments are subject to availability.</li>
                            <li>You will receive a confirmation once your appointment is approved.</li>
                            <li>Please arrive 10 minutes before your scheduled time.</li>
                            <li>Bring your ID and any relevant medical documents.</li>
                        </ul>
                        <hr>
                        <p class="text-muted"><small>Office Hours: 9:00 AM - 4:00 PM</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
