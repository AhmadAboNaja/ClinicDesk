<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-user-edit mr-2"></i>Edit User</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=users">Users</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
                        <h3 class="card-title">Edit User: <?php echo htmlspecialchars($edittingUser['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                    <form method="POST" action="index.php?page=users&action=edit&id=<?php echo $edittingUser['id']; ?>">
                        <div class="card-body">
                            <?php echo CSRF::input(); ?>
                            <div class="form-group">
                                <label for="name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($edittingUser['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($edittingUser['email'], ENT_QUOTES, 'UTF-8'); ?>" readonly disabled>
                                <small class="form-text text-muted">Email address cannot be changed.</small>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($edittingUser['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars(ucfirst($edittingUser['role']), ENT_QUOTES, 'UTF-8'); ?>" readonly disabled>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?php echo $edittingUser['is_active'] ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="is_active">Active User</label>
                                </div>
                                <small class="form-text text-muted">Inactive users cannot login to the system.</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>
                            <a href="index.php?page=users" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>User Details</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>User ID:</strong></td>
                                <td><?php echo (int) $edittingUser['id']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td><?php echo isset($edittingUser['created_at']) ? htmlspecialchars(formatDate($edittingUser['created_at']), ENT_QUOTES, 'UTF-8') : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Last Login:</strong></td>
                                <td><?php echo isset($edittingUser['last_login']) && $edittingUser['last_login'] ? htmlspecialchars($edittingUser['last_login'], ENT_QUOTES, 'UTF-8') : 'Never'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
