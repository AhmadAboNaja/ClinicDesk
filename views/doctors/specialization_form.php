<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-plus-circle mr-2"></i><?php echo isset($specialization) ? 'Edit' : 'Add'; ?> Specialization</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=specializations">Specializations</a></li>
                    <li class="breadcrumb-item active"><?php echo isset($specialization) ? 'Edit' : 'Create'; ?></li>
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
                        <h3 class="card-title">Specialization Information</h3>
                    </div>
                    <form method="POST" action="index.php?page=specializations&action=<?php echo isset($specialization) ? 'edit&id=' . $specialization['id'] : 'create'; ?>">
                        <div class="card-body">
                            <?php echo CSRF::input(); ?>
                            <div class="form-group">
                                <label for="name">Specialization Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required 
                                       value="<?php echo isset($specialization) ? htmlspecialchars($specialization['name'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                       placeholder="e.g., Cardiology, Neurology, Pediatrics...">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter a brief description of this specialization..."><?php echo isset($specialization) ? htmlspecialchars($specialization['description'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> <?php echo isset($specialization) ? 'Update' : 'Create'; ?> Specialization
                            </button>
                            <a href="index.php?page=specializations" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>Examples</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Common Specializations:</strong></p>
                        <ul class="mb-0">
                            <li>Cardiology</li>
                            <li>Dermatology</li>
                            <li>Endocrinology</li>
                            <li>Gastroenterology</li>
                            <li>Neurology</li>
                            <li>Oncology</li>
                            <li>Ophthalmology</li>
                            <li>Orthopedics</li>
                            <li>Pediatrics</li>
                            <li>Psychiatry</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
