<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-user-plus mr-2"></i>Create User</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=users">Users</a></li>
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
                        <h3 class="card-title">New User Information</h3>
                    </div>
                    <form method="POST" action="index.php?page=users&action=create">
                        <div class="card-body">
                            <?php echo CSRF::input(); ?>
                            <div class="form-group">
                                <label for="name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Enter full name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="temp_password">Temporary Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="temp_password" name="temp_password" required minlength="6" placeholder="Enter password (min 6 characters)">
                                <small class="form-text text-muted">Minimum 6 characters required.</small>
                            </div>
                            <div class="form-group">
                                <label for="role">Role <span class="text-danger">*</span></label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="patient">Patient</option>
                                    <option value="doctor">Doctor</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Create User
                            </button>
                            <a href="index.php?page=users" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>Help</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Role Descriptions:</strong></p>
                        <ul>
                            <li><strong>Admin:</strong> Full access to manage the system</li>
                            <li><strong>Doctor:</strong> Can view appointments and manage prescriptions</li>
                            <li><strong>Patient:</strong> Can book appointments and view prescriptions</li>
                        </ul>
                        <p class="text-muted"><small>Users will be required to change their password on first login.</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
