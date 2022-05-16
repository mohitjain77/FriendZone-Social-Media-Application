<?php
session_start();
include('../classes/post.php');

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: ../allpages/login.php');
}


$display = new Display();
$user = $display->get_user($_SESSION['username']);
//for posting
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $display = new Display();
    $username = $_SESSION['username'];
    $postresult = $display->generate_post($username, $_POST, $_FILES);
    if($postresult == "")
    {
        header("location: profilepost.php");
        die;
    }
    else{
        echo "<div style='text-align:center;font-size:12px;color:white;backgroud-color:lightgray";
        echo "<br>Look into this errors:<br><br>";
        echo $postresult;
        echo "</div>";
    }
}

$username = $_SESSION['username'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylesheet/bootstrap.min.css">
    <link rel="shortcut icon" href="../helping_images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../stylesheet/main.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="../jsfiles/myFile.js"></script>

    <title>Your Profile</title>
</head>
<body>
    <div class="profilepost">
        <div class="container-fluid">
            <div class="row">
                <div class="navbar sticky-top">
                    <?php               
                        $pic = "../helping_images/profilepicture.jpeg";
                        if(file_exists($user['profile_pic']) != "")
                        {
                            $pic = $user['profile_pic'];
                        }
                    ?>
                    <img src="../helping_images/FriendZonelogo.png" class="mylogo">
                    <a class="miniprofileimagelink" href="profilepost.php"><img src="<?php echo $pic ?>" class="miniprofileimage"></a>
                    <a class="logoutitem" href="../helping_php/logout.php" name='logout'>Logout</a>
                    <a class="aboutlink" href="aboutpage.php">About</a>
                    <a class="homelink" href="index.php">Home</a>
                    
                </div>
                <?php
                    $display = new Display();
                    $user = $display->get_user($_SESSION['username']);

                    $coverpic = "../helping_images/defaultcover.jpeg";
                    if(file_exists($user['cover_pic']) != "")
                    {
                        $coverpic = $user['cover_pic'];
                    }
                ?>
                <div class="coverarea" style="background:url('<?php echo $coverpic ?>');background-size: cover;background-position: center;background-repeat: no-repeat;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-4 col-sm-offset-4">
                                <div class="box">
                                    
                                    <div class="profilepic" style="background:url('<?php echo $pic ?>');background-repeat: no-repeat;background-size: cover;background-position: center"></div>
                                    <div class= "changepic" style="font-size: 11px;">
                                    <a href="../helping_php/replace_profile_pic.php">Edit Profile or Cover</a></div>
                                    <div class="profilename">
                                    <?php
                                    $display = new  Display();
                                        $r_user = $display->get_user($username);
                                        echo $r_user['fullname'];
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="postarea">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="post">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="post-top">
                                            <div class="dp">
                                                <img src="<?php echo $pic ?>" class="profile1">
                                            </div>
                                            <textarea name="post" class="post-content" type="text" placeholder="What's on your mind?"></textarea>
                                        </div>
                                        <div class="post-bottom">
                                            <div class="action">
                                                <i class="far fa-image"></i>
                                                <span>Photo</span>
                                            </div>
                                            <input type= "file" name="imagefile" style="padding:3px; opacity:0.9; color:#9d004e; border: 1px solid #9d004e; border-radius:10px; height:30px">
                                            <input class="post-button" type= "submit" value="Post">
                                        </div>
                                    </form>
                                </div>
                                <div class="post-box">
                                    <?php
                                        $page_no = isset($_GET['mypage']) ? (int)$_GET['mypage'] : 1;
                                        $page_no = ($page_no < 1) ? 1 : $page_no;
                                        $definelimit = 3;
                                        $markoffset = ($page_no - 1) * $definelimit;
                                        $query = "select * from posts where username = '$username' and parent = 0 order by id desc limit $definelimit offset $markoffset";
                                        $postresult = $display->view($query);
                                        if(isset($postresult) && $postresult){
                                            foreach($postresult as $r){
                                                $display = new Display();
                                                $r_user = $display->get_user($r['username']);
                                                
                                                include("../helping_php/postcontent.php");
                                            }
                                        }

                                    ?>
                                </div>
                                <div class="pagination">
                                    <li class="page-item previous-page"><a class="page-link" href="profilepost.php?mypage=<?php echo ($page_no - 1) ?>">Prev</a></li>
                                    <li class="page-item next-page"><a class="page-link" href="profilepost.php?mypage=<?php echo ($page_no + 1) ?>">Next</a></li>
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
