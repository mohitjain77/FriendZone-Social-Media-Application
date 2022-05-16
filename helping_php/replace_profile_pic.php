<?php
session_start();

include('../classes/post.php');

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: ../allpages/login.php');
}
$post = new Display();
$USERDATA = $post->get_user($_SESSION['username']);


if($_SERVER["REQUEST_METHOD"] == "POST")
{

    if(isset($_FILES['profile_file']['name']) && $_FILES['profile_file']['name'] != ""){

        $uploaddir = '../my_images/' . $_SESSION['username'] . "/";
            if(!file_exists($uploaddir))
            {
                mkdir($uploaddir,0777,true);
            }
        $uploadfile = $uploaddir . basename($_FILES['profile_file']['name']);
        $result = move_uploaded_file($_FILES['profile_file']['tmp_name'], $uploadfile);
        
        if(file_exists($uploadfile))
        {
            $conn = mysqli_connect("localhost", "root", "", "mydatabase");
            $user_name = $USERDATA['username'];
            $query = "update registration set profile_pic = '$uploadfile' where username = '$user_name' limit 1";
            $q = mysqli_query($conn, $query);
            $_POST['is_profilepicture'] = 1;
            $post = new Display();
            $postresult = $post->generate_post($user_name, $_POST, $uploadfile);
            header("location: ../allpages/profilepost.php");
            die;
        }

    }else{
        echo "<div style='text-align:center;font-size:12px;color:white;backgroud-color:lightgray";
        echo "<br>Look into this errors:<br><br>";
        echo "Please add a valid image before hitting the change button!";
        echo "</div>";  
    }

    if(isset($_FILES['cover_file']['name']) && $_FILES['cover_file']['name'] != ""){

        $uploaddir = '../my_images/' . $_SESSION['username'] . "/";
            if(!file_exists($uploaddir))
            {
                mkdir($uploaddir,0777,true);
            }
        $uploadfile = $uploaddir . basename($_FILES['cover_file']['name']);
        $result = move_uploaded_file($_FILES['cover_file']['tmp_name'], $uploadfile);
        
        if(file_exists($uploadfile))
        {
            $conn = mysqli_connect("localhost", "root", "", "mydatabase");
            $user_name = $USERDATA['username'];
            $query = "update registration set cover_pic = '$uploadfile' where username = '$user_name' limit 1";
            $q = mysqli_query($conn, $query);
            $_POST['is_coverpicture'] = 1;
            $post = new Display();
            $postresult = $post->generate_post($user_name, $_POST, $uploadfile);


            
            header("location: ../allpages/profilepost.php");
            die;
        }

    }else{
        echo "<div style='text-align:center;font-size:12px;color:white;backgroud-color:lightgray";
        echo "<br>Look into this errors:<br><br>";
        echo "Please add a valid image before hitting the change button!";
        echo "</div>";  
    }
        
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../helping_images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../stylesheet/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="../stylesheet/main.css" />
    <title>Your Profile</title>
</head>
<body>
    <div class="profilepost">
        <div class="container-fluid">
            <div class="row">
                <div class="navbar sticky-top">
                    <img src="../helping_images/FriendZonelogo.png" class="mylogo">
                    <a class="logouticon" href="logout.php" name= "logout">Logout</a>
                    <a class= "abouticon" href="../allpages/aboutpage.php" name= "about">About</a>
                    <a class="homeicon" href="../allpages/index.php">Home</a>
                </div>
                <div class="profileupload">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <div class="post">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="post-bottom">
                                            <h4>Choose your Profile Image:</h4>
                                            <input name="profile_file" class="profilefile" type="file" style="margin-left:30px;">
                                            <input class="post-button" type= "submit" value="Change">
                                        </div>
                                    </form>
                                </div>
                                <div class="post">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="post-bottom">
                                            <h4>Choose your Cover Image:</h4>
                                            <input name="cover_file" class="coverfile" type="file" style="margin-left:30px;">
                                            <input class="post-button" type= "submit" value="Change">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>