<?php
$value = file_get_contents("php://input");
if($value != ''){
    $value = json_decode($value);
}

if(isset($value->action) && $value->action == "post_like")
{
    include("../ajax/likepage.ajax.php");
}
?>