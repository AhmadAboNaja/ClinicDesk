<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ClinicDesk | Login</title>
<link rel="stylesheet" href="/public/assets/adminlte/css/adminlte.min.css">
<style>
html, body { height: 100%; margin: 0; padding: 0; }
body { display: flex; align-items: center; justify-content: center; background-color: #f0f2f5; }
.login-box { width: 360px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.1); padding: 30px; }
.login-box h2 { text-align: center; margin-bottom: 30px; color: #333; }
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-weight: 500; }
input[type="email"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
input[type="email"]:focus, input[type="password"]:focus { outline: 0; border-color: #007bff; }
.form-group input[type="submit"] { width: 100%; background: #007bff; color: #fff; border: none; padding: 10px; border-radius: 4px; font-size: 16px; font-weight: 500; cursor: pointer; }
.form-group input[type="submit"]:hover { background: #0056b3; }
.alert { padding: 12px 15px; margin-bottom: 15px; border-radius: 4px; }
.alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>
</head>
<body>
<div class="login-box">
    <h2>ClinicDesk</h2>
    <?php displayFlash(); ?>
    <form method="POST" action="index.php?page=login">
        <?php echo CSRF::input(); ?>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Login">
        </div>
    </form>
</div>
</body>
</html>
