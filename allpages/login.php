<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "mydatabase");

$username = $password = "";
$err = "";
$error = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password']))){
        $err = "Username or Password is missing";
        echo "$err";
        exit();
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }

if(empty($err)){
    $sql = "SELECT id, username, password FROM registration WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $param_usrername);
    $param_usrername = $username;
    
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1){
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
            if(mysqli_stmt_fetch($stmt)){
                if(password_verify($password, $hashed_password)){
                    session_start();
                    $_SESSION['username'] = $username;
                    $_SESSION['id'] = $id;
                    $_SESSION['loggedin'] = true;

                    header('location: index.php');
                }
                else{
                    $error="Password is wrong";
                    // echo $error;
                }
            }
        }
        else{
            $error="This username doesn't exists";
            // echo $error;
        }
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friendzone App</title>

    <!-- Here I have linked CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link rel="shortcut icon" href="../helping_images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../stylesheet/bootstrap.min.css">
    <link rel="stylesheet" href="../stylesheet/registerandlogin.css">
    <!-- Here I have linked Javascript -->
    
    <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    

</head>

<body>
    <div class="signin">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <div class="box">
                        <div class="container-fluid">
                            <div class="row">
                                <form id="login-form" class="login-form" action="" method="post">
                                    <div class="logo"></div><br>
                                    <input class="username" id= "username" type="text" placeholder="Username" name="username"><br>
                                    <div id="username_error">Please fill the form</div>
                                    <input class="password" id= "password" type="password" placeholder="Password" name="password">
                                    <div id="password_error">Please fill the form</div>
                                    <button type="submit" name="button" id="submit" class="submit">Login</button><br>
                                    <div style="color:red;"><?php $er=$error; echo $er; ?></div>
                                </form>
                                or New Customer? Please Register<br><br>
                                <input class="submit" type="button" onclick="window.location.href='register.php'" value="Register"><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {

            $("#username_error").hide();
            $("#password_error").hide();


            var error_username = false;
            var error_password = false;


            $(".username").focusout(function() {
            check_username();
            });
            $(".password").focusout(function() {
            check_password();
            });

            function check_username() {
            var pattern = /^[a-zA-Z0-9_]*$/;
            var username = $(".username").val()
            if (pattern.test(username) && username !== '') {
                $("#username_error").hide();
                $(".username").css("border-bottom","2px solid #34F458");
            } else {
                $("#username_error").html("No Special characters are allowed");
                $("#username_error").show();
                $(".username").css("border-bottom","2px solid #F90A0A");
                error_fullname = true;
            }
            }

            function check_password() {
            var password_length = $(".password").val().length;
            if (password_length < 8) {
                $("#password_error").html("Password must be wrong");
                $("#password_error").show();
                $(".password").css("border-bottom","2px solid #F90A0A");
                error_password = true;
            } else {
                $("#password_error").hide();
                $(".password").css("border-bottom","2px solid #34F458");
            }
            }

            


            $("#login-form").submit(function() {
            error_username = false;
            error_password = false;

            check_username();
            check_password();

            if (error_username === false && error_password === false) {
                // alert("Logged in  Successfully");
                return true;
            } else {
                alert("Credentials are incorrect.");
                return false;
            }


            });
        });                         
    </script>
</body>

</html>
