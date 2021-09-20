<?php
    session_start();
    $username = 'admin';
    $password = 'admin';
    if (isset($_POST['submit'])) {
        if ($_POST['username'] == $username && $_POST['password'] == $password){
            //Membuat Session
            $_SESSION["username"] = $username; 
            echo "Anda Berhasil Login $username";
            /*Pindah Ke Halaman index.php*/
            header("Location: index.php");
        } else {
            echo '<script>alert("Username Atau Password Tidak Benar")</script>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
        <label for="username">username</label>
        <input type="text" name="username" id="username">
        <label for="password">password</label>
        <input type="password" name="password" id="password">
        <input type="submit" name="submit" value="submit">
    </form>    
</body>
</html>