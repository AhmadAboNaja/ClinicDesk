<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Appointments</h1>
        <?php if (Auth::role() === 'patient'): ?>
            <a href="index.php?page=appointments&action=book" class="btn">Book Appointment</a>
        <?php endif; ?>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <div class="card-header">
            <h3>Appointments List</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <?php if (Auth::role() === 'admin' || Auth::role() === 'patient'): ?>
                            <th>Doctor</th>
                            <th>Specialization</th>
                        <?php endif; ?>
                        <?php if (Auth::role() === 'admin' || Auth::role() === 'doctor'): ?>
                            <th>Patient</th>
                        <?php endif; ?>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($appointments)): ?>
                        <tr><td colspan="7">No appointments found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($appointments as $appt): ?>
                            <tr>
                                <?php if (Auth::role() === 'admin' || Auth::role() === 'patient'): ?>
                                    <td><?php echo htmlspecialchars($appt['doctor_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($appt['specialization'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                <?php endif; ?>
                                <?php if (Auth::role() === 'admin' || Auth::role() === 'doctor'): ?>
                                    <td><?php echo htmlspecialchars($appt['patient_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                <?php endif; ?>
                                <td><?php echo htmlspecialchars(formatDate($appt['appt_date']), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(formatTime($appt['appt_time']), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(strtoupper($appt['status']), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php if (Auth::role() === 'patient' && $appt['status'] === 'pending'): ?>
                                        <form method="POST" action="index.php?page=appointments&action=cancel&id=<?php echo $appt['id']; ?>" style="display:inline;">
                                            <?php echo CSRF::input(); ?>
                                            <button type="submit" class="btn" style="background:#dc3545;">Cancel</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
