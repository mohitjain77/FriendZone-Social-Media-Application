<?php

$conn = mysqli_connect("localhost", "root", "", "mydatabase");

$fullname = $username = $email = $password = $confirmpassword = "";
$fullname_err = $username_err = $email_err = $password_err = $confirmpassword_err = "";


if ($_SERVER['REQUEST_METHOD'] == "POST"){

    if (empty(trim($_POST['fullname']))){
        $fullname_err = "Full name cannot be blank";
        echo $fullname_err;
        exit();
    }
    else{
        $fullname = trim($_POST['fullname']);
    }


    //Check if username is empty
    if(empty(trim($_POST['username']))){
        $username_err = "Username cannot be blank";
        echo $username_err;
        exit();
    }
    else{
        $sql = "SELECT id FROM registration WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['username']);

            //Executing this statement 
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken";
                    echo $username_err;
                    exit();
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong";
                exit();
            }
        }
        
    }
    mysqli_stmt_close($stmt);

    

    if (empty(trim($_POST['email']))){
        $email_err = "Email Id cannot be blank";
        echo $email_err;
        exit();
    }
    elseif(filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) == false){
        $email_err = "Input format of Email Id is not correct";
        echo $email_err;
        exit();
    }
    else{
        $sql = "SELECT id FROM registration WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set the value of param username
            $param_email = trim($_POST['email']);

            //Executing this statement 
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $email_err = "This Emailid is already exist";
                    echo $email_err;
                    exit();
                }
                else{
                    $email = trim($_POST['email']);
                }
            }
            else{
                echo "Something went wrong";
                exit();
            }
        }
        
    }
    mysqli_stmt_close($stmt);

    

    if (empty(trim($_POST['password']))){
        $password_err = "Password cannot be blank";
        echo $password_err;
        exit();
    }
    elseif(strlen(trim($_POST['password'])) < 5){
        $password_err = "Password cannot be less than 5 characters";
        echo $password_err;
        exit();
    }
    else{
        $password = trim($_POST['password']);
    }

    if(trim($_POST['password']) != trim($_POST['confirmpassword'])){
        $password_err = "Passwords should match";
        echo $password_err;
        exit();
    }

    // If there were no errors, go ahead and insert into the password
    if(empty($fullname_err) && empty($username_err) && empty($email_err) && empty($password_err)){

        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];


        $selectquery = "SELECT * from registration where username = '$username'";

        $query = mysqli_query($conn, $selectquery);
        $result = mysqli_fetch_assoc($query);
        
        if(empty($result)){
            $hashpass = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO registration(fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$hashpass')";
            mysqli_query($conn, $sql);
            header("location: login.php");
            exit();
        } else{
            echo "User already exist. Try Signing in!";
            exit();

        }
    }

    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Here I have linked CSS -->
    <link rel="shortcut icon" href="../helping_images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../stylesheet/bootstrap.min.css">
    <link rel="stylesheet" href="../stylesheet/registerandlogin.css">
    <!-- Here I have linked Javascript -->
    <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
    <!-- <script src="myFile.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
</head>

<body>
    <div class="signup">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <div class="box">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="logo"></div>
                                <h4 class="message">Sign up to see photos from your friends.</h4>
                                <form id="register-form" action="" method="post">          
                                    <input class="fullname" type="text" placeholder="Full name" name="fullname">
                                    <div id="fullname_error">Please fill the form</div>
                                    <input class="username" type="text" placeholder="Username" name="username">
                                    <div id="username_error">Please fill the form</div>
                                    <input class="email" type="text" placeholder="Email Id" name="email">
                                    <div id="email_error">Please fill the form</div>
                                    <input class="password" type="password" placeholder="Password" name="password">
                                    <div id="password_error">Please fill the form</div>
                                    <input class="confirmpassword" type="password" placeholder="Confirm Password" name="confirmpassword">
                                    <div id="confirmpassword_error">Please fill the form</div>

                                    <button type="submit" name="button" class="submit">Sign up</button>
                                </form>
                                
                                Already have an account?
                                <a id="login-link"
                                    href="login.php">Log in</a>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {

            $("#fullname_error").hide();
            $("#username_error").hide();
            $("#email_error").hide();
            $("#password_error").hide();
            $("#confirmpassword_error").hide();

            var error_fullname = false;
            var error_username = false;
            var error_email = false;
            var error_password = false;
            var error_confirmpassword = false;

            $(".fullname").focusout(function(){
            check_fullname();
            });
            $(".username").focusout(function() {
            check_username();
            });
            $(".email").focusout(function() {
            check_email();
            });
            $(".password").focusout(function() {
            check_password();
            });
            $(".confirmpassword").focusout(function() {
            check_confirmpassword();
            });

            function check_fullname() {
            var pattern = /^[a-zA-Z ]*$/;
            var fullname = $(".fullname").val();
            if (pattern.test(fullname) && fullname !== '') {
                $("#fullname_error").hide();
                $(".fullname").css("border-bottom","2px solid #34F458");
            } else {
                $("#fullname_error").html("Should contain only Characters");
                $("#fullname_error").show();
                $(".fullname").css("border-bottom","2px solid #F90A0A");
                error_fullname = true;
            }
            }

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
                $("#password_error").html("Atleast 8 Characters");
                $("#password_error").show();
                $(".password").css("border-bottom","2px solid #F90A0A");
                error_password = true;
            } else {
                $("#password_error").hide();
                $(".password").css("border-bottom","2px solid #34F458");
            }
            }

            function check_confirmpassword() {
            var password = $(".password").val();
            var confirmpassword = $(".confirmpassword").val();
            if (password !== confirmpassword) {
                $("#confirmpassword_error").html("Passwords Did not Matched");
                $("#confirmpassword_error").show();
                $(".confirmpassword").css("border-bottom","2px solid #F90A0A");
                error_confirmpassword = true;
            } else {
                $("#confirmpassword_error").hide();
                $(".confirmpassword").css("border-bottom","2px solid #34F458");
            }
            }

            function check_email() {
            var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            var email = $(".email").val();
            if (pattern.test(email) && email !== '') {
                $("#email_error").hide();
                $(".email").css("border-bottom","2px solid #34F458");
            } else {
                $("#email_error").html("Invalid Email");
                $("#email_error").show();
                $(".email").css("border-bottom","2px solid #F90A0A");
                error_email = true;
            }
            }

            $("#register-form").submit(function() {
            error_fullname = false;
            error_username = false;
            error_email = false;
            error_password = false;
            error_confirmpassword = false;

            check_fullname();
            check_username();
            check_email();
            check_password();
            check_confirmpassword();

            if (error_fullname === false && error_username === false && error_email === false && error_password === false && error_confirmpassword === false) {
                alert("Registration Successfull");
                return true;
            } else {
                alert("Please Fill the form Correctly");
                return false;
            }


            });
        });                         
    </script>
</body>

</html>