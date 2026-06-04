<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Prescription Details</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <h3>Diagnosis</h3>
            <p><?php echo nl2br(htmlspecialchars($prescription['diagnosis'], ENT_QUOTES, 'UTF-8')); ?></p>
            
            <h3>Medications</h3>
            <p><?php echo nl2br(htmlspecialchars($prescription['medications'], ENT_QUOTES, 'UTF-8')); ?></p>
            
            <?php if ($prescription['notes']): ?>
                <h3>Notes</h3>
                <p><?php echo nl2br(htmlspecialchars($prescription['notes'], ENT_QUOTES, 'UTF-8')); ?></p>
            <?php endif; ?>
            
            <?php if ($prescription['file_path']): ?>
                <h3>Attachment</h3>
                <a href="index.php?page=prescriptions&action=download&id=<?php echo $prescription['id']; ?>" class="btn">Download PDF</a>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <a href="index.php?page=prescriptions" class="btn" style="background:#6c757d;">Back</a>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
