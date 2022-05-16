
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
        <div class="post-info">
            <?php $display = new Display();
            if($display->my_post($r['postid'],$_SESSION['username']))
            {?>
            <a style="color: black" href='../allpages/profilepost.php'>
            <?php } ?>
            <span class="name1" >
            <?php echo $r_user['fullname']?></span></a>
            <?php
                if($r['is_profilepicture'])
                {
                echo "<span style='opacity: 0.8; font-weight:lighter;'> has updated his/her profile picture</span>";
                }
                if($r['is_coverpicture'])
                {
                echo "<span style='opacity: 0.8; font-weight:lighter;'> updated his/her cover picture.</span>";
                }
            ?>
            
            <div class="time1" style="opacity:0.7"><?php $newDate = date("d-m-Y", strtotime($r['date'])); echo $newDate; ?></div>
        </div>
        <i>
        <?php
            $display = new Display();
            if($display->my_post($r['postid'],$_SESSION['username']))
            {?>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle fas fa-ellipsis-h" type="button" data-toggle="dropdown" style="background-color:transparent;border:0;color:black"></button>
            <ul class="dropdown-menu" style="min-width:60px">
            
            <li><a href='../helping_php/editpage.php?id=<?php echo $r['postid']?>'>Edit</a></li>
            <li><a data-toggle='modal' data-target='#Modal' type="button" href='' onclick= "myfunction(<?php echo $r['postid'] ?>)">Delete</a></li>
                
            </ul>
        </div>
        <?php } ?>
        </i>
    </div>
    <div class="post-content">
        <?php echo $r['post'] ?>
        <img src='
        <?php 
                if ($r['has_picture'] == 1 || $r['is_profilepicture'] == 1 || $r['is_coverpicture'] == 1){
                    echo $r['picture'];
                }
        ?>'>
        
    </div>
    <div class="post-bottom">
        <div class="action">
            <i class="far fa-thumbs-up"></i>
            <?php
                $likes = "";
                $likes = ($r['likes'] > 0) ? $r['likes'] : "";
            ?>
            <a style="color:#9d004e" onclick="post_like(event)" href="../helping_php/likepage.php?type=post&id=<?php echo $r['postid'] ?>"> Like <?php echo $likes ?></a>
            
        </div>
        <div class="action">
            <i class="far fa-comment"></i>
            <?php
                $comments = "";
                
                $comments = ($r['comments'] > 0) ? $r['comments'] : "";
            ?>
            <a href="../helping_php/solopost.php?id=<?php echo $r['postid'] ?>"><span style="color:#9d004e">Comment<?php echo $comments ?></span></a>
        </div>
    </div>
    <div class="post-bottom" style="margin-left:15px;margin-top:0;padding:0; color:#9d004e">
        <?php
            $my_like = false;
            if(isset($_SESSION['username'])){
                $query = "select likes from likescounter where type='post' && matterid = '$r[postid]' limit 1";
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
        ?>
            <?php echo "<a id='info_$r[postid]' href = '../helping_php/likepage.php?type=post&id=$r[postid]'>";
            if($r['likes'] >0){
                if($r['likes'] == 1){
                    if(!$my_like){
                        echo "<div style='float:left; color:#9d004e'>You person liked this post.</div>";
                    }else{
                        echo"<div style='float:left; color:#9d004e'> 1 person liked this post.</div>";    
                    }
                }else{
                    if(!$my_like){
                        $t = "others";
                        if($r['likes'] - 1 == 1){
                            $t = "other";
                        }
                        echo "<div style='float:left; color:#9d004e'>You and " . ($r['likes'] - 1) . " liked this post.</div>";
                    }else{
                        echo "<div style='float:left; color:#9d004e'>" . $r['likes'] . " liked this post.</div>";
                    }
                }
            }
            echo "</a>";

        ?>
    </div><br>
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

<script type="text/javascript">
    function my_ajax_data(value, element){

        var ajax = new XMLHttpRequest();

        ajax.addEventListener('readystatechange', function(){

            if(ajax.readyState == 4 && ajax.status == 200){

                response(ajax.responseText, element);
            }
        });
       
        value = JSON.stringify(value);
        ajax.open("post", '../helping_php/ajax.php',true);
        ajax.send(value);
    }

    function response(result, element){
        
        if(result != "")
        {
            var object = JSON.parse(result);
            if(typeof object.action != 'undefined'){
                if (object.action == 'post_like'){
                    var likes = "";
                    likes = (parseInt(object.likes) > 0) ? "Like (" +object.likes+ ")" : "Like";
                    element.innerHTML = likes;

                    var info_ele = document.getElementById(object.id);
                    info_ele.innerHTML = object.info;
                }
                
            }
            
        }
        
    }

    function post_like(event){
        event.preventDefault();
        var e = event.target || event.srcElement;
        var link = e.getAttribute('href');
        var value = {};
        value.link = link;
        value.action = "post_like";
        my_ajax_data(value, event.target);
    }

</script>




