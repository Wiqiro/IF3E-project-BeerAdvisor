<?php
session_start();
require_once("connection.php");
global $bdd;
if (isset($_POST['confirm'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $request = $bdd->prepare("SELECT * FROM user WHERE Username = ?");
    $request->execute(array($username));
    $res = $request->fetch(PDO::FETCH_ASSOC);

    if ($res) {
        $passwordHash = $res['Password'];
        if (password_verify($password, $passwordHash)) {
            $_SESSION['ID'] = $res['ID'];
            $_SESSION['Username'] = $res['Username'];
            $_SESSION['Admin'] = $res['Admin'];
            header("Location:Profile.php?id=" . $_SESSION['ID']);
        } else {
            $error = "Wrong password";
        }
    } else {
        $error = "Wrong username";
    }
}
?>


<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Beer advisor</title>
        <meta name="author" content="Quetin,Eloi,Willianm">
        <meta name="description" content="Page to connect or subscribe">
        <link rel="shortcut icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="body">
        <!-- HEADER -->
        <div class="header">
            <div class="image"><a href="Browse-Beers.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
            <div class="header_title">Sign-in</div>
            <div class="header_buttons">
            <?php
            if ($_SESSION['ID'] != 0) {
                echo '<button id="header" onclick="window.location.href=`Profile.php?id=' . $_SESSION['ID'] . '`">Profile</button>
                <button onclick="window.location.href=`sign-out.php`">Sign-out</button>';
            } else {
                echo '<button onclick="window.location.href=`Sign-in.php`">Sign-in</button>
                <button onclick="window.location.href=`Sign-up.php`">Sign-up</button>';
            }
            ?>
            </div>
        </div>
        <hr>
        <!-- HEADER -->
        <form method="post">
            <div class="sign">
                <table>
                    <tr>
                        <td>
                            <label for="username"></label>
                            <input type="text" name="username" id="username" placeholder="Username" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="password"></label>
                            <input type="password" name="password" id="password" placeholder="Password" required>
                        </td>
                    </tr>
                </table>
                <?php
                if (isset($error)) {
                    echo $error;
                }
                ?>
                <input class="button" type="submit" name="confirm" alt="sign in" value="Sign in">
                <hr>
                <p class="container">Not register yet ? <a href="Sign-up.php">Sign up</p>
            </div>
        </form>
    </body>
</html>