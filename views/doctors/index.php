<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-user-md mr-2"></i>Doctor Management</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Doctors</li>
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
                        <h3 class="card-title">All Doctors</h3>
                        <div class="card-tools">
                            <a href="index.php?page=doctors&action=create" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Add Doctor
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="mb-3">

                            <input type="hidden" name="page" value="doctors">

                            <div class="row">

                                <div class="col-md-5">
                                    <input type="text"
                                        name="search"
                                        class="form-control"
                                        placeholder="Search doctor name or email"
                                        value="<?php echo $_GET['search'] ?? ''; ?>">
                                </div>

                                <div class="col-md-4">
                                    <select name="specialization" class="form-control">
                                        <option value="">All Specializations</option>
                                        <?php foreach ($specializations ?? [] as $sp): ?>
                                            <option value="<?= $sp['id'] ?>"
                                                <?= (($_GET['specialization'] ?? '') == $sp['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($sp['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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
                                        <th>Photo</th>
                                        <th>Name</th>

                                        <th>Specialization</th>
                                        <th>Consultation Fee</th>
                                        <th>Available Days</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($doctors)): ?>
                                        <?php foreach ($doctors as $doc): ?>

                                            <tr>
                                                <td style="width:90px;">
                                                    <?php if (!empty($doc['profile_photo'])): ?>
                                                        <img src="/ClinicDesk/public/uploads/doctor_photos/<?= htmlspecialchars($doc['profile_photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Doctor photo" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                                    <?php else: ?>
                                                        <i class="fas fa-user-md text-info"></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td>

                                                    <div class="d-flex align-items-center">
                                                        <div class="user-img mr-2">
                                                            <i class="fas fa-user-md fa-2x text-info"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-weight-bold"><?php echo htmlspecialchars($doc['user_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo htmlspecialchars($doc['specialization'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                                                </td>
                                                <td><?php echo isset($doc['consultation_fee']) ? '$' . number_format($doc['consultation_fee'], 2) : 'N/A'; ?></td>
                                                <td><?php echo htmlspecialchars($doc['available_days'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <a href="index.php?page=doctors&action=edit&id=<?php echo $doc['user_id']; ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="fas fa-user-md fa-2x mb-2"></i><br>
                                                No doctors found.
                                            </td>
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