<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12 text-center">
                <h1 class="m-0">Error</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="error-page">
                    <div class="error-content">
                        <h1><i class="fas fa-ban text-warning"></i> 403</h1>
                        <h3>Access Denied</h3>
                        <p class="text-muted">You do not have permission to access this resource.</p>
                        <a href="index.php?page=dashboard" class="btn btn-primary">
                            <i class="fas fa-home mr-1"></i> Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<style>
.error-page {
    text-align: center;
    padding: 50px 0;
}
.error-content h1 {
    font-size: 100px;
    font-weight: 700;
    color: #ffc107;
}
.error-content h3 {
    font-size: 28px;
    margin: 20px 0;
}
</style>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
