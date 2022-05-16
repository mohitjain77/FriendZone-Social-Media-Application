<div class="post">
    <div class="post-top">
        <div class="dp">
            <?php
                $pic = "../helping_images/profilepicture.jpeg";
                if(file_exists($r_user['profile_pic']) != "")
                {
                    $pic = $r_user['profile_pic'];
                }
            ?>
            <a href='../allpages/profilepost.php'><img src="<?php echo $pic ?>" alt='' class="profile1"></a>
        </div>
        <div class="post-info" "><a style="color: black" href='../allpages/profilepost.php'>
            <span class="name1" >
            <?php echo $r_user['fullname']?></span></a>
            <?php
                if($comment_r['is_profilepicture'])
                {
                echo "<span style='opacity: 0.8; font-weight:lighter;'> has updated his/her profile picture</span>";
                }
                if($comment_r['is_coverpicture'])
                {
                echo "<span style='opacity: 0.8; font-weight:lighter;'> updated his/her cover picture.</span>";
                }
            ?>
            
            <div class="time1" style="opacity:0.7"><?php $newDate = date("d-m-Y", strtotime($comment_r['date'])); echo $newDate; ?></div>
        </div>
        <i>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle fas fa-ellipsis-h" type="button" data-toggle="dropdown" style="background-color:transparent;border:0;color:black"></button>
            <ul class="dropdown-menu" style="min-width:60px">
            <?php
                $display = new Display();
                if($display->my_post($comment_r['postid'],$_SESSION['username']))
                {?>
                    <li><a href='../helping_php/editpage.php?id=<?php echo $comment_r['postid']?>'>Edit</a></li>
                    <li><a data-toggle='modal' data-target='#Modal' type="button" href='' onclick= "myfunction(<?php echo $comment_r['postid'] ?>)">Delete</a></li>
                <?php } ?>
            </ul>
        </div>
        </i>
    </div>
    <div class="post-content">
        <?php echo $comment_r['post'] ?>
        <img src='
        <?php 
                if ($comment_r['has_picture'] == 1 || $comment_r['is_profilepicture'] == 1 || $comment_r['is_coverpicture'] == 1){
                    echo $comment_r['picture'];
                }
        ?>'>
        
    </div>
    <div class="post-bottom">
        <div class="action">
            <i class="far fa-thumbs-up"></i>
            <?php
                $likes = "";
                $likes = ($comment_r['likes'] > 0) ? $comment_r['likes'] : "";
            ?>
            <a href="../helping_php/likepage.php?type=post&id=<?php echo $comment_r['postid'] ?>"><span style="color:#9d004e">Like <?php echo $likes ?></span></a>
            
        </div>
        <div class="action">
            <i class="far fa-comment"></i>
            <a href="../helping_php/solopost.php?id=<?php echo $comment_r['postid'] ?>"><span style="color:#9d004e">Reply</span></a>
        </div>
    </div>
    <div class="post-bottom" style="margin-left:15px;margin-top:0;padding:0; color:#9d004e">
        <?php
            if($comment_r['likes'] > 0){
                if($comment_r['likes'] == 1 and $comment_r['username'] != $_SESSION['username']){
                    echo "<span style='float:left;'>" . $comment_r['likes'] . " person liked this post.</span>";
                }
                elseif($comment_r['likes'] == 1 and $comment_r['username'] == $_SESSION['username']){
                    echo "<span style='float:left;'>" . "You liked this post.</span>";
                }
                elseif($comment_r['likes'] == 2 and $comment_r['username'] == $_SESSION['username']){
                    echo "<span style='float:left;'>" . "You and " . $comment_r['likes'] ." person liked this post.</span>";
                }
                elseif($comment_r['likes'] > 1 and $comment_r['username'] == $_SESSION['username']){
                    echo "<span style='float:left;'>" . "You and " . $comment_r['likes'] ." people liked this post.</span>";
                }
                else{
                    echo "<span style='float:left;'>" . $comment_r['likes'] . " persons liked this post.</span>";
                }
            }
        ?>
    </div>
    
</div>
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Are you sure you want to delete this post?
        </div>
        <div class="modal-footer">
            <input id="delete_id" type="hidden" name="" value="">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button onclick= "delete_post()" type="button" class="btn btn-primary">Delete</button>
        </div>
        </div>
    </div>
</div>