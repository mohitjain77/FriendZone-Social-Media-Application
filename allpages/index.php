<?php
include("../classes/createtable.php");

session_start();
include("../classes/post.php");


$display = new Display();
$_SESSION['username'] = isset($_SESSION['username']) ? $_SESSION['username'] : "";
$user_data = $display->check_login($_SESSION['username'], false);

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $post = new Display();
    $username = $_SESSION['username'];
    $postresult = $post->generate_post($username, $_POST, $_FILES);
    if($postresult == "")
    {
        header("location: index.php");
        die;
    }
    else{
        echo "<div style='text-align:center;font-size:12px;color:white;backgroud-color:lightgray";
        echo "<br>Look into this errors:<br><br>";
        echo $postresult;
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
    <link rel="stylesheet" href="../stylesheet/bootstrap.min.css">
    <link rel="shortcut icon" href="../helping_images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="../stylesheet/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="../jsfiles/myFile.js"></script>
    <title>Home Page</title>
</head>

<body>
    <div class="homepage">
        <div class="container-fluid">
            <div class="row">
                <div class="navbar">
                    <?php
                        $display = new Display();

                        if(!isset($_SESSION['loggedin'])){
                            $user = array('id' => "", 'fullname' => "", 'username' => "", 'email' => "", 'password' => "", 'profile_pic' => "", 'cover_pic' => "", 'phone' => "", 'dob' => "", 'address' =>"", 'bio' =>"", 'created_by' => "" );
                        }
                        else
                        {
                            $user = $display->get_user($_SESSION['username']);
                            $pic = "../helping_images/profilepicture.jpeg";
                            if(file_exists($user['profile_pic']) != "")
                            {
                                $pic = $user['profile_pic'];
                            }
                        }

                        
                    ?>
                    <?php
                    if(!isset($_SESSION['loggedin'])){
                        include('../helping_php/registernav.php');
                    }else{
                        include('../helping_php/navigation.php');
                    }
                        
                    ?>
                </div>
                <div class="postarea">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <?php
                                if(isset($_SESSION['loggedin'])){ ?>
                                     <div class="post">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="post-top">
                                                <div class="dp">
                                                    <a href="profilepost.php"><img src="<?php echo $pic ?>" class="profile1"></a>
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
                                <?php } ?>
                               
                                <div class="post-box">
                                    <?php

                                        $page_no = isset($_GET['mypage']) ? (int)$_GET['mypage'] : 1;
                                        $page_no = ($page_no < 1) ? 1 : $page_no;
                                        $definelimit = 3;
                                        $markoffset = ($page_no - 1) * $definelimit;
                                        $display = new Display();
                                        $query = "select * from posts where parent = 0 order by id desc limit $definelimit offset $markoffset";
                                        $postresult = $display->view($query);
                                        
                                        if($postresult){
                                            foreach($postresult as $r){
                                                $display = new Display();
                                                $r_user = $display->get_user($r['username']);

                                                
                                                include("../helping_php/postcontent.php");  
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="pagination">
                                    <li class="page-item previous-page"><a class="page-link" href="index.php?mypage=<?php echo ($page_no -1) ?>">Prev</a></li>
                                    <li class="page-item next-page"><a class="page-link" href="index.php?mypage=<?php echo ($page_no + 1) ?>">Next</a></li>
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



