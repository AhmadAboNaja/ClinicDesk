<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Specializations</h1>
        <a href="index.php?page=specializations&action=create" class="btn">Add Specialization</a>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <div class="card-header">
            <h3>All Specializations</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($specializations as $spec): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($spec['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <form method="POST" action="index.php?page=specializations&action=delete&id=<?php echo $spec['id']; ?>" style="display:inline;">
                                    <?php echo CSRF::input(); ?>
                                    <button type="submit" class="btn" style="background:#dc3545;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
