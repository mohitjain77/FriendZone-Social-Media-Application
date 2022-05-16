<?php

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: login.php');
}
include("../classes/post.php");
$display = new Display();
$user = $display->get_user($_SESSION['username']);
$conn = mysqli_connect("katara.scam.keele.ac.uk", "x6d04", "x6d04x6d04", "x6d04");
$query = "select sum(likes),sum(comments) from posts where username = '$_SESSION[username]'";
$result = mysqli_query($conn,$query);
$row =  mysqli_fetch_array($result);
$query1 = "select * from registration where username = '$_SESSION[username]'";
$result1 = mysqli_query($conn,$query1);
$r =  mysqli_fetch_array($result1);


if($_SERVER['REQUEST_METHOD'] == "POST"){
    $display = new Display();
    $display->change_info($_POST);
    header("location: aboutpage.php");
    die;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="shortcut icon" href="../helping_images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../stylesheet/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="../stylesheet/main.css" />

    <title>About</title>
</head>

<body>
    <div class="about">
        <div class="container-fluid">
            <div class="navbar">
                <?php
                $pic = "../helping_images/profilepicture.jpeg";
                $covpic = "../helping_images/defaultcover.jpeg";
                if(file_exists($user['profile_pic']) != "")
                {
                    $pic = $user['profile_pic'];
                }
                if(file_exists($user['cover_pic']) != "")
                {
                    $covpic = $user['cover_pic'];
                }
                ?>
                <img src="../helping_images/FriendZonelogo.png" class="mylogo">
                <a class="miniprofileimagelink" href="profilepost.php"><img src="<?php echo $pic ?>" class="miniprofileimage"></a>
                <a class="logouticon" href="../helping_php/logout.php" name= "logout">Logout</a>
                <a class="profileicon" href="profilepost.php">Profile</a>
                <a class="homeicon" href="index.php">Home</a>
            </div>
            <div class= "container">
                <div class="row">
                    <div class="col-md-4 mt-1">
                        <div class="card text-center sidebar">
                            
                            <div class="card-body" style="background: url(<?php echo $covpic ?>); background-repeat: no-repeat; background-size: cover; background-position: center;">
                                <img src=<?php echo $pic ?> class="rounded-circle" style="width:150px; height:150px; border-radius:50%">
                                <div class="mt-3">
                                    <h3><?php echo $r['fullname'] ?></h3>
                                    <span class="like-counter"><img src="../helping_images/likegreylogo.jpeg"
                                            class="like-logo"><br><?php echo $row[0] ?></span>
                                    <span class="comment-counter"><img src="../helping_images/commentgreylogo.png"
                                            class="comment-logo"><br><?php echo $row[1] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mt-1">
                        <form  enctype="multipart/form-data" action="aboutpage.php" method="post">
                            <div class="card mb-3 content">
                                <h1 class="about">About</h1>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5>Full Name</h5>
                                        </div>
                                        <div class="col-md-9 text-secondary">
                                            <?php echo $r['fullname'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5>Email</h5>
                                        </div>
                                        <div class="col-md-9 text-secondary">
                                        <?php echo $r['email'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5>Phone</h5>
                                        </div>
                                        <div class="col-md-9 text-secondary">
                                            <input name = 'phone' type= 'number' class='phone-number' style='width:100%;border:0;border-color:transparent' value='<?php echo $r['phone'] ?>'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5>Date of birth</h5>
                                        </div>
                                        <div class="col-md-9 text-secondary">
                                        <input type='date' name='dob' class='dob' style='width:100%;border:0;border-color:transparent' value='<?php echo $r['dob'] ?>'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5>Address</h5>
                                        </div>
                                        <div class="col-md-9 text-secondary">
                                        <textarea name= 'address' class='address' style='width:100%;border:0;border-color:transparent'><?php echo $r['address'] ?></textarea>
                                            
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5>Bio</h5>
                                        </div>
                                        <div class="col-md-9 text-secondary">
                                        <textarea name= 'bio' class='bio' style='width:100%;border:0;border-color:transparent'><?php echo $r['bio']?></textarea>
                                        </div>
                                    </div> 
                                    <hr>                     
                                    <div class="row">
                                        <div class="col-md-9 col-md-offset-3 text-secondary">
                                        <input class="post-button" type= "submit" style="float:right;margin-left:40px,width:100px;margin-top:0; height: 30px; border: 2px solid #9d004e; border-radius: 25px; color:#9d004e" value="Save">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
</body>

</html>