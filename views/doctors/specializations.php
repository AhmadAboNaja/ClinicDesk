<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-stethoscope mr-2"></i>Specializations</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Specializations</li>
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
                        <h3 class="card-title">All Specializations</h3>
                        <div class="card-tools">
                            <a href="index.php?page=specializations&action=create" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Add Specialization
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="mb-3">

                            <input type="hidden" name="page" value="specializations">

                            <div class="row">

                                <div class="col-md-9">
                                    <input type="text"
                                        name="search"
                                        class="form-control"
                                        placeholder="Search specialization name..."
                                        value="<?php echo $_GET['search'] ?? ''; ?>">
                                </div>

                                <div class="col-md-3">
                                    <button class="btn btn-primary btn-block">
                                        Search
                                    </button>
                                </div>

                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 60%;">Name</th>
                                        <th style="width: 20%;">Doctors Count</th>
                                        <th style="width: 20%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($specializations)): ?>
                                        <?php foreach ($specializations as $spec): ?>
                                            <tr>
                                                <td>
                                                    <i class="fas fa-stethoscope text-info mr-2"></i>
                                                    <strong><?php echo htmlspecialchars($spec['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo (int) ($spec['doctor_count'] ?? 0); ?> Doctors</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="index.php?page=specializations&action=edit&id=<?php echo $spec['id']; ?>" class="btn btn-sm btn-info" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" action="index.php?page=specializations&action=delete&id=<?php echo $spec['id']; ?>" style="display:inline;">
                                                            <?php echo CSRF::input(); ?>
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this specialization?');">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                <i class="fas fa-stethoscope fa-2x mb-2"></i><br>
                                                No specializations found.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>