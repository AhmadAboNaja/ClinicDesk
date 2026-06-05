<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-chart-bar mr-2"></i>Appointment Reports</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Reports</li>
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
            <!-- Report Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Generate Report</h3>
                    </div>
                    <form method="POST" action="index.php?page=reports">
                        <div class="card-body">
                            <?php echo CSRF::input(); ?>
                            <div class="form-group">
                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required value="<?php echo htmlspecialchars($_POST['start_date'] ?? date('Y-m-01'), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required value="<?php echo htmlspecialchars($_POST['end_date'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="doctor_id">Doctor</label>
                                <select class="form-control" id="doctor_id" name="doctor_id">
                                    <option value="">-- All Doctors --</option>
                                    <?php foreach ($doctors as $doctor): ?>
                                        <option value="<?php echo $doctor['user_id']; ?>" <?php echo (($_POST['doctor_id'] ?? '') == $doctor['user_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($doctor['user_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">-- All --</option>
                                    <option value="pending" <?php echo (($_POST['status'] ?? '') === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo (($_POST['status'] ?? '') === 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="completed" <?php echo (($_POST['status'] ?? '') === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo (($_POST['status'] ?? '') === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-bar mr-1"></i> Generate Report
                            </button>
                            <button type="submit" name="export" value="csv" class="btn btn-success btn-block">
                                <i class="fas fa-file-csv mr-1"></i> Export CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Report Results -->
            <div class="col-md-8">
                <?php if (isset($reportData) && !empty($reportData)): ?>
                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo count($reportData); ?></h3>
                                <p>Total Appointments</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <?php
                    $statusCounts = [];
                    foreach ($reportData as $row) {
                        $status = $row['status'] ?? 'unknown';
                        $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
                    }
                    $colors = ['pending' => 'warning', 'confirmed' => 'primary', 'completed' => 'success', 'cancelled' => 'danger'];
                    $icons = ['pending' => 'clock', 'confirmed' => 'check', 'completed' => 'check-double', 'cancelled' => 'times'];
                    foreach ($statusCounts as $status => $count):
                    ?>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-<?php echo $colors[$status] ?? 'secondary'; ?>">
                            <div class="inner">
                                <h3><?php echo $count; ?></h3>
                                <p><?php echo ucfirst($status); ?></p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-<?php echo $icons[$status] ?? 'question'; ?>"></i>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Results Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-table mr-1"></i> Report Results</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reportData as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(formatDate($row['appt_date']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars(formatTime($row['appt_time']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['patient_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['doctor_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $colors[$row['status']] ?? 'secondary'; ?>">
                                                <?php echo htmlspecialchars(ucfirst($row['status']), ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php elseif (isset($reportData)): ?>
                <div class="card">
                    <div class="card-body text-center text-muted py-5">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <h5>No Results Found</h5>
                        <p>No appointments match the selected criteria.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
