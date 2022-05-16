<?php

include('post.php');
$display = new Display();

function my_content($r){
    
    $username = $_SESSION['username'];
    if(isset($r['postid']))
    {
        if($username == $r['username'])
        {
            return TRUE;
        }else{

            $single_post = $display->fetch_post($r['parent']);
            if($username == $single_post['username']){
                return TRUE;
            }
        }
        
    }

    return FALSE;
}

?>