<?php
    session_start();

    include('../classes/post.php');
    
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header('location: ../allpages/login.php');
    }
    if(isset($_SERVER['HTTP_REFERER'])){
        $come_back = $_SERVER['HTTP_REFERER'];
    }
    else{
        $come_back = "../allpages/profilepost.php";
    }
    
    if(isset($_GET['type']) && isset($_GET['id'])){
        if(is_numeric($_GET["id"])){

            $permit[] = "post";
            $permit[] = "profile";
            $permit[] = "comment";

            if(in_array($_GET['type'], $permit)){
                $display = new Display();
                $display->like_counter($_GET['id'], $_GET['type'], $_SESSION['username']);
            }
        }
    }
    header("location: ". $come_back);
    die;
?>