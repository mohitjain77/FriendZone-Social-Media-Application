<?php
    include('../classes/post.php');
    $postid = $_GET['id'];
    $display = new Display();
    $display->delete_post($postid);

?>