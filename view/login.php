<!DOCTYPE html>
<html>
<head>
    <title>LOGIN</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
    <form action="index.php" method="post">
        <h2>LOGIN</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>User Name</label>
        <input type="text" name="uname" placeholder="User Name"><br>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password"><br>
        <input type="hidden" name="action" value="login">
        <button type="submit" class="login-btn">Login</button>
        <div class="footer-btn">
            <span>Dont have an account?</span>
            <a href="view/signup.php" class="ca">Register Now!</a>
        </div>
    </form>
</body>
</html>
