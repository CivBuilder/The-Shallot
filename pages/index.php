<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h1>login here</h1>

    

    <form action="login.php" method="post">
        Username: <input type="text" name="username" id=""><br>
        Password: <input type="password" name="password" id=""><br>
        <input type="submit" value="Login">
    </form>

    <?php

    // if error checking username and password
    if(isset($_GET['err'])){
        echo '<p style="color: red;">',$_GET['err'],'</p>';
    }
    ?>
    <br>
    <a href="register.php">Click to Register</a>
    <br> 
    <a href="changePassword.php">Click to Change Password</a>
</body>
</html>