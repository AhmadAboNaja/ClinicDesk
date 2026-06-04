<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>My Prescriptions</h1>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <div class="card-header">
            <h3>Prescriptions</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Diagnosis</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prescriptions)): ?>
                        <tr><td colspan="4">No prescriptions found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($prescriptions as $presc): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($presc['doctor_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(formatDate($presc['appt_date']), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(substr($presc['diagnosis'], 0, 50), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a href="index.php?page=prescriptions&action=view&id=<?php echo $presc['id']; ?>" class="btn">View</a>
                                    <?php if ($presc['file_path']): ?>
                                        <a href="index.php?page=prescriptions&action=download&id=<?php echo $presc['id']; ?>" class="btn">Download</a>
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
