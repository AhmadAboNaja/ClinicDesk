<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-users mr-2"></i>User Management</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Users</h3>
                        <div class="card-tools">
                            <a href="index.php?page=users&action=create" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Add User
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'doctor' ? 'success' : 'info'); ?>">
                                                    <?php echo htmlspecialchars(ucfirst($user['role']), ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <?php if ($user['is_active']): ?>
                                                    <span class="badge badge-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
    <a href="index.php?page=users&action=edit&id=<?php echo $user['id']; ?>"
       class="btn btn-sm btn-info">
        <i class="fas fa-edit"></i> Edit
    </a>

    <form method="POST"
          action="index.php?page=users&action=toggleActive&id=<?php echo $user['id']; ?>"
          style="display:inline-block;"
          onsubmit="return confirm('Change user status?');">

        <?php echo CSRF::input(); ?>

        <?php if ($user['is_active']): ?>
            <button type="submit" class="btn btn-sm btn-warning">
                <i class="fas fa-user-slash"></i> Deactivate
            </button>
        <?php else: ?>
            <button type="submit" class="btn btn-sm btn-success">
                <i class="fas fa-user-check"></i> Activate
            </button>
        <?php endif; ?>

    </form>
</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No users found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <?php if (isset($paginator)): ?>
                            <?php echo $paginator->render(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
