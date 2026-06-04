<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Doctor Dashboard</h1>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>Today's Appointments</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($todayAppointments)): ?>
                <table class="table">
                    <tr><th>Time</th><th>Patient</th><th>Reason</th></tr>
                    <?php foreach ($todayAppointments as $appt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(formatTime($appt['appt_time']), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($appt['patient_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($appt['reason'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No appointments today.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
