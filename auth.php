<?php 
    session_name('myApp');
    session_start([
        'cookie_lifetime' => 300,
    ]);
    //session_destroy();
    $error = false;

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $fp = fopen("./data/userpass.txt", "r");
    if ($username && $password) {
        $_SESSION['loggedin']  = false;
        $_SESSION['user'] = false;
        $_SESSION['role'] = false;
        while($data = fgetcsv($fp)){
            if ($data[0] == $username && $data[1] == sha1($password)) {
                $_SESSION['loggedin']  = true;
                $_SESSION['user'] = $username;
                $_SESSION['role'] = $data[2];
                header('location:index.php');
            }
        }
            if (false == $_SESSION['loggedin']) {
                $error = true;
            }
    }
    if(isset($_GET['logout'])){
        $_SESSION['loggedin']  = false;
        $_SESSION['user'] = false;
        $_SESSION['role'] = false;
        session_destroy();
        header('location:index.php');
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Example</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="//cdn.rawgit.com/necolas/normalize.css/master/normalize.css">
    <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
    <style>
        body {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="column column-60 column-offset-20">
            <h2>Authentication</h2>
        </div>
    </div>
        <div class="row">
        <div class="column column-60 column-offset-20">
            <?php if (true == $_SESSION['loggedin']) {
                echo "<p>We will use this in crud. Welcome {$_SESSION['user']} to page.</p>";
            }else{
                echo "<p>We will use this in crud. First login below.</p>";
            } ?>
        </div>
    </div>

    <div class="row">
        <div class="column column-60 column-offset-20">
            <?php 
            if($error){
                echo "<blockquote>You have entered wrong Username or Password.</blockquote>";
            }
            if(false == $_SESSION['loggedin']): ?>
            <form method="post">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <button type="submit" class="button-primary" name="submit">Log In</button>
            </form>
            <?php
        else:
            ?>
            <form action="/crud/auth.php" method="post">
                <input type="hidden" name="logout" value="1">
                <button type="submit" class="button-primary" name="submit">Log Out</button>
            </form>
        <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>