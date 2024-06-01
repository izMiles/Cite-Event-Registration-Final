<!-- signup.php -->

<!DOCTYPE html>
<html>
<head>
    <title>SIGN UP</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <form action="../controller/signupController.php" method="post">
        <h2>SIGN UP</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
            <p class="success"><?php echo $_GET['success']; ?></p>
        <?php } ?>

        <label>Full Name</label>
        <?php if (isset($_GET['name'])) { ?>
            <input type="text" name="name" placeholder="Name" value="<?php echo $_GET['name']; ?>"><br>
        <?php }else{ ?>
            <input type="text" name="name" placeholder="Name"><br>
        <?php }?>

        <label>User Name</label>
        <?php if (isset($_GET['uname'])) { ?>
            <input type="text" name="uname" placeholder="User Name" value="<?php echo $_GET['uname']; ?>"><br>
        <?php }else{ ?>
            <input type="text" name="uname" placeholder="User Name"><br>
        <?php }?>

        <label>Password</label>
        <input type="password" name="password" placeholder="Password"><br>

        <label>Confirm Password</label>
        <input type="password" name="re_password" placeholder="Confirm Password"><br>

        <label>Department</label>
        <select name="department">
            <option value="Electrical Department">Electrical Department</option>
            <option value="Computer Department">Computer Department</option>
            <option value="Mechanical Department">Mechanical Department</option>
            <option value="Electronics Department">Electronics Department</option>
        </select><br>

        <label>Section</label>
        <input type="text" name="section" placeholder="Section"><br>

        <button type="submit">Sign Up</button>
        <a href="../index.php" class="ca">Already have an account?</a>
    </form>
</body>
</html>
