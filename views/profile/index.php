<?php

$userName = htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8');

?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-user-edit mr-2"></i>Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <?php displayFlash(); ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Update your account</h3>
                    </div>

                    <form method="POST" action="index.php?page=profile">
                        <div class="card-body">
                            <?php echo CSRF::input(); ?>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= $userName; ?>" required>
                            </div>

                            <hr>

                            <div class="form-group">
                                <label for="current_password">Current password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <small class="form-text text-muted">Required to change your password.</small>
                            </div>

                            <div class="form-group">
                                <label for="new_password">New password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm new password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

