<?php
session_start();
require_once("connection.php");
global $bdd;
$id = $_SESSION['ID'];
if (isset($_GET['id']) and $_GET['id'] > 0) {
    $profileid = intval($_GET['id']);
    $req_profile = $bdd->prepare("SELECT * FROM user WHERE id = ?");
    $req_profile->execute(array($_GET['id']));
    $profile_data = $req_profile->fetch();
    $username = $profile_data['Username'];
    $date = $profile_data['Creation_date'];
    $bio = $profile_data['Bio'];

    $req_friend = $bdd->prepare("SELECT COUNT(*) FROM follows WHERE Follower_ID = ? AND Followed_ID = ?");
    $req_friend->execute(array($id, $profileid));
    $result_friend = $req_friend->fetch();
    if(isset($_POST['Add_friend']) && $result_friend == 0){
        $req_follow = $bdd->prepare("INSERT INTO follows (Followed_ID,Follower_ID) VALUES (?,?)");
        $req_follow->execute(array($profileid, $id));
        $req_follow->fetch();
    }
    if ($result_friend == 1) {
        $message = 'Following';
    }

}


?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Beer advisor</title>
        <meta name="author" content="Quentin,Eloi,William">
        <meta name="description" content="This is a page about beer">
        <link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="image"><a href="index.php"><img src="BeerAdvisor.png" alt="logo"></a></div><br>
        <div>
            <table><tr>
                <td>
					<div class="image" ><?php echo '<img id="profile_picture" src="data:image;base64,' . base64_encode($profile_data["Picture"]) . '" alt=""/>'; ?></div>
                </td>
                <td>
                Profile: <?php
                echo $username . '<br>
                Member since: ' . $date . '<br>Bio: <br>' .
                $bio . '<br>'
                ?><br>
                </td>
            </tr></table>
            
            <?php
            if ($id == $profileid) {
                echo '<a href="Edit-profile.php">Edit profile</a>';
            } else if (isset($message)) {
                echo $message;
            } else {
                echo '<form method="post">
                        <input type="submit" id="Add_friend" name="Add_friend" Value="Follow">
                    </form>';
            }
            ?>

        </div>
    </body>
</html>