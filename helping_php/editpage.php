<?php
    session_start();
    include('../classes/post.php');
    
    
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header('location: ../allpages/login.php');
    }
    $err = "";
    $display = new Display();
    
    if(isset($_GET['id'])){

        $row = $display->fetch_post($_GET['id']);

        if(!$row){

            $err = "Post doesn't exist";
        }else{
            if($row['username'] != $_SESSION['username']){
                $err = "Access denied, you are not autorised to delete this post!";
            }
        }
    }else{
        $err = "Post doesn't exist";
    }

    if(isset($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "editpage.php")){
        $_SESSION['come_back'] = $_SERVER['HTTP_REFERER'];
    }

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $display->change_post($_POST,$_FILES);
        header("location: ".$_SESSION['come_back']);
        die;
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
    <title>Delete page</title>
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
                                            <h4 style='margin:20px'>Edit Post</h4>
                                            <?php
                                            
                                            if($err != ""){
                                                echo $err;
                                            }else{ ?>   
                                                <div class="container">
                                                <textarea name='post' class='post-content' type='text' style='width: 100%'><?php echo $row['post'] ?></textarea>
                                                <input type= "file" name="imagefile" style="padding:3px; opacity:0.9; color:#9d004e; border: 1px solid #9d004e; border-radius:10px; height:30px;width: 40%">
                                                <input type='hidden' name='postid' value='<?php echo $row['postid']?>'>
                                                <input class="post-button" type= "submit" value="Save" style="margin-left:40px; min-width:60px; margin: 5px">
                                                <a href='<?php echo $_SESSION['come_back'] ?>'><input  class="post-button" type= "button" value="Discard" style="min-width:60px; margin: 5px"></a><hr>
                                                <?php
                                                if(file_exists($row['picture']))
                                                { ?>
                                                    <div><img src='<?php echo $row['picture'] ?>' style='width:100%'></div>
                                               <?php } ?>
                                                </div>

                                            <?php } ?>
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