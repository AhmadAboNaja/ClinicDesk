<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Admin Dashboard</h1>
    </div>
    <div class="row">
        <?php if (!empty($totalByRole)): ?>
            <?php foreach ($totalByRole as $roleStat): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h3><?php echo htmlspecialchars(ucfirst($roleStat['role']), ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p style="font-size: 2rem; font-weight: bold;"><?php echo (int) $roleStat['total']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>Today's Appointments</h3>
        </div>
        <div class="card-body">
            <p>Total: <?php echo (int) $todayAppointments; ?></p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
