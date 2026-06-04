<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Doctors</h1>
        <a href="index.php?page=doctors&action=create" class="btn">Add Doctor</a>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <div class="card-header">
            <h3>All Doctors</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Fee</th>
                        <th>Available Days</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doc): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($doc['user_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($doc['specialization'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo isset($doc['consultation_fee']) ? number_format($doc['consultation_fee'], 2) : 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($doc['available_days'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <a href="index.php?page=doctors&action=edit&id=<?php echo $doc['id']; ?>" class="btn">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
