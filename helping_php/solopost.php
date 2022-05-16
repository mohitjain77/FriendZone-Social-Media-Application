<?php
    session_start();

    include('../classes/post.php');

    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header('location: ../allpages/login.php');
    }
    
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $display = new Display();
        $username = $_SESSION['username'];
        $postresult = $display->generate_post($username, $_POST, $_FILES);
        if($postresult == "")
        {
            header("location: solopost.php?id=$_GET[id]");
            die;
        }
        else{
            echo "<div style='text-align:center;font-size:12px;color:white;backgroud-color:lightgray";
            echo "<br>Look into this errors:<br><br>";
            echo $postresult;
            echo "</div>";
        }
    }
    $display = new Display();
    $r = false;
    $err = "";

    if(isset($_GET['id'])){
        $r = $display->fetch_post($_GET['id']);
    }else{
        $err = "Post not found!";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylesheet/bootstrap.min.css">
    <link rel="shortcut icon" href="../helping_images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="../stylesheet/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="../jsfiles/myFile.js"></script>
    <title>Comment on post</title>
</head>

<body>
    <div class="homepage">
        <div class="container-fluid">
            <div class="row">
                <div class="navbar">
                    <?php
                        $display = new Display();
                        $user = $display->get_user($_SESSION['username']);
                        $pic = "../helping_images/profilepicture.jpeg";
                        if(file_exists($user['profile_pic']) != "")
                        {
                            $pic = $user['profile_pic'];
                        }
                    ?>
                    <img src="../helping_images/FriendZonelogo.png" class="mylogo">
                    <a class="miniprofileimagelink" href="../allpages/profilepost.php"><img src="<?php echo $pic ?>" class="miniprofileimage"></a>
                    <a class="logoutitem" href="../helping_php/logout.php" name='logout'>Logout</a>
                    <a class="aboutlink" href="../allpages/aboutpage.php">About</a>
                    <a class="homelink" href="../allpages/index.php">Home</a>
                </div>
                <div class="postarea">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="post-box">
                                    <?php
                                        if(is_array($r)){
                                            $display = new Display();
                                            $r_user = $display->get_user($r['username']);
                                            include("postcontent.php");  
                                        }
                                    ?>
                                    <div class="post">
                                        <form method = 'post' enctype='multipart/form-data'>
                                            <div class = comment-textarea>
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            <textarea name="post" class="comment-content"  type="text" placeholder="Type a comment..." style="margin:0"></textarea>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="hidden" name="parent" value="<?php echo $r['postid'] ?>">
                                                            <input  class="post-button" type= "submit" value="Post">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                <?php
                                                    $comments = $display->fetch_comments($r['postid']);
                                                    if(is_array($comments)){
                                                        foreach($comments as $comment_r){
                                                            $r_user = $display->get_user($comment_r['username']);
                                                            include("commentpage.php");
                                                        }
                                                    }
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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