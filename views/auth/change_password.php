<?php
// Change password form for first_login users
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Change your password</h3>
                        </div>

                        <form method="POST" action="index.php?page=change_password">
                            <div class="card-body">
                                <?php echo CSRF::input(); ?>

                                <div class="form-group">
                                    <label for="current_password">Current password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>

                                <div class="form-group">
                                    <label for="new_password">New password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <small class="form-text text-muted">
                                        Min 8 chars, includes lowercase + uppercase + number.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">Confirm new password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>

                                <div class="alert alert-warning mb-0">
                                    You must change your password before continuing.
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

