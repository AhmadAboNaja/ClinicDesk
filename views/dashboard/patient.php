<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Patient Dashboard</h1>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4>Active Appointments</h4>
                    <p style="font-size: 2rem; font-weight: bold; color: #007bff;"><?php echo (int) $active; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4>Completed</h4>
                    <p style="font-size: 2rem; font-weight: bold; color: #28a745;"><?php echo (int) $completed; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4>Prescriptions</h4>
                    <p style="font-size: 2rem; font-weight: bold; color: #ffc107;"><?php echo (int) $prescriptionCount; ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php if ($nextAppointment): ?>
        <div class="card">
            <div class="card-header">
                <h3>Next Appointment</h3>
            </div>
            <div class="card-body">
                <p><strong>Doctor:</strong> <?php echo htmlspecialchars($nextAppointment['doctor_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars(formatDate($nextAppointment['appt_date']), ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Time:</strong> <?php echo htmlspecialchars(formatTime($nextAppointment['appt_time']), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
