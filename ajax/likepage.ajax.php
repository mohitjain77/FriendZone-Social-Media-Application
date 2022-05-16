<?php
session_start();
include('../classes/post.php');

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: ../allpages/login.php');
}

$display = new Display();
$query_string = explode("?", $value->link);
$query_string = end($query_string);

$str = explode("&", $query_string);

foreach($str as $v){
    $v = explode("=", $v);
    $_GET[$v[0]] = $v[1];
}
    $_GET['id'] = addslashes($_GET['id']);
    $_GET['type'] = addslashes($_GET['type']);
    if(isset($_GET['type']) && isset($_GET['id'])){
        if(is_numeric($_GET["id"])){

            $permit[] = "post";
            $permit[] = "profile";
            $permit[] = "comment";

            if(in_array($_GET['type'], $permit)){
                
                $display->like_counter($_GET['id'], $_GET['type'], $_SESSION['username']);
            }
        }


        $likes = $display->fetch_likes($_GET['id'], $_GET['type']);
        $likes_count = count($likes);
        $likes = array();
        $info = "";
        $my_like = false;
        
        if(isset($_SESSION['username'])){
            $query = "select likes from likescounter where type='post' && matterid = '$_GET[id]' limit 1";
            $display = new Display();
            $q1 = $display->view($query);
            if(is_array($q1)){
                $likes = json_decode($q1[0]['likes'], true);
                $user_name = array_column($likes, 'username');
                if(!in_array($_SESSION['username'], $user_name)){
                    $my_like = true;
                }
            }
        }
        $count_like = count($likes);

        if($count_like > 0){
            if($count_like == 1){
                if(!$my_like){
                    $info .= "<div style='float:left;color:#9d004e'>You liked this post.</div>";
                }else{
                    $info .= "<div style='float:left;color:#9d004e'> 1 person liked this post.</div>";    
                }
            }else{
                if(!$my_like){
                    $t = "others";
                    if($count_like - 1 == 1){
                        $t = "other";
                    }
                    $info .= "<div style='float:left;color:#9d004e'>You and " . ($count_like - 1) . " liked this post.</div>";
                }else{
                    $info .= "<div style='float:left;color:#9d004e'>" . $count_like . " liked this post.</div>";
                }
            }
            
        }
        
        $object = (object)[];
        $object->likes = count($likes);
        $object->action = "post_like";
        $object->id = "info_$_GET[id]";
        $object->info = $info;
        echo json_encode($object);
    }

?>