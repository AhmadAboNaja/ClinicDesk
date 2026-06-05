<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-user-plus mr-2"></i>Create Doctor</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=doctors">Doctors</a></li>
                    <li class="breadcrumb-item active">Create</li>
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
                        <h3 class="card-title">New Doctor Information</h3>
                    </div>
                    <form method="POST" action="index.php?page=doctors&action=create">
                        <div class="card-body">
                            <?php echo CSRF::input(); ?>
                            <div class="form-group">
                                <label for="user_id">User ID <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="user_id" name="user_id" required placeholder="Enter user ID">
                                <small class="form-text text-muted">The user must already exist with the 'doctor' role.</small>
                            </div>
                            <div class="form-group">
                                <label for="specialization_id">Specialization <span class="text-danger">*</span></label>
                                <select class="form-control" id="specialization_id" name="specialization_id" required>
                                    <option value="">-- Select Specialization --</option>
                                    <?php foreach ($specializations as $spec): ?>
                                        <option value="<?php echo $spec['id']; ?>"><?php echo htmlspecialchars($spec['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="consultation_fee">Consultation Fee</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control" id="consultation_fee" name="consultation_fee" step="0.01" value="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Available Days</label>
                                <div class="row">
                                    <?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day): ?>
                                    <div class="col-md-auto">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="day_<?php echo $day; ?>" name="available_days[]" value="<?php echo $day; ?>">
                                            <label for="day_<?php echo $day; ?>" class="custom-control-label"><?php echo $day; ?></label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bio">Biography</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Enter doctor biography..."></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Create Doctor
                            </button>
                            <a href="index.php?page=doctors" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>Help</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Steps to create a doctor profile:</strong></p>
                        <ol>
                            <li>Create a user with the 'doctor' role first</li>
                            <li>Enter the user ID in the form</li>
                            <li>Select the doctor's specialization</li>
                            <li>Set the consultation fee (optional)</li>
                            <li>Select available days</li>
                            <li>Add a biography (optional)</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
