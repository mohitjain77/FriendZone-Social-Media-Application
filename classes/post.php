<?php
include('../helping_php/connect.php');

class Display
{
    public function __construct()
        {
            $db = new DatabaseConnection;
            $this->conn = $db->conn;
        }

    public function generate_post($username, $data, $files)
    {
        if(!empty($data["post"]) || !empty($files['imagefile']['name']) || isset($data['is_profilepicture']) || isset($data['is_coverpicture']))
        {
            $error = "";
            $picture= "";
            $has_picture= 0;
            $is_profilepicture = 0;
            $is_coverpicture = 0;
            $post='';
            if(isset($data['post'])){
                $post = addslashes($data['post']);
            }

            if(isset($data['is_profilepicture']) || isset($data['is_coverpicture']))
            {
                $picture = $files;
                $has_image = 1;
                if(isset($data['is_profilepicture']))
                {
                    $is_profilepicture = 1;
                }
                if(isset($data['is_coverpicture']))
                {
                    $is_coverpicture = 1;
                }
                
                
            }else{
                if(!empty($files['imagefile']['name']))
                {
                    $uploaddir = '../my_images/' . $username . "/";
                    if(!file_exists($uploaddir))
                    {
                        mkdir($uploaddir,0777,true);
                    }
                    $picture = $uploaddir . basename($_FILES['imagefile']['name']);
                    $result = move_uploaded_file($_FILES['imagefile']['tmp_name'], $picture);

                    $has_picture= 1;
                }
            }
            
            $postid = $this->generate_postid();
            $parent = 0;
            if(isset($data['parent']) && is_numeric($data['parent'])){
                $parent = $data['parent'];

                $sql = "update posts set comments = comments + 1 where postid = '$parent' limit 1";
                $this->conn->query($sql);
            }
            $query = "insert into posts (username, postid, post, picture, has_picture, is_profilepicture, is_coverpicture, parent) values ('$username', '$postid', '$post', '$picture', '$has_picture', '$is_profilepicture', '$is_coverpicture', '$parent')";
            $this->conn->query($query);
        }else{
            $error = "Please type something to post!<br>";
        }
        return $error;
    }

    private function generate_postid()
    {
        $length = rand(4,11);
        $number = "";
        for ($i=0; $i < $length; $i++)
        {
            $new_rand = rand(0,9);
            $number = $number . $new_rand;
        }
        return $number;

    }
    public function view($query)
    {
        $result = $this->conn->query($query);

        if(!$result)
        {
            return false;
        }else
        {
            $data = false;
            while($r = mysqli_fetch_assoc($result))
            {
                $data[] = $r;
            }
            return $data;
        }

    }
    public function get_posts($username)
    {
        $query = "select * from posts where username = '$username' and parent = '0' order by id desc limit 12";
 
        $q = $this->view($query);
        if($q)
        {
            return $q;
        }else{
            return false;
        }
    }
    public function fetch_comments($id)
    {
        $query = "select * from posts where parent = '$id' order by id asc limit 12";
 
        $q = $this->view($query);
        if($q)
        {
            return $q;
        }else{
            return false;
        }
    }

    public function fetch_likes($id, $type)
    {
        $type = addslashes($type);
        if(is_numeric($id)){
            
            $query = "select likes from likescounter where type='$type' && matterid = '$id' limit 1";
            $q1 = $this->view($query);
            if(is_array($q1)){

                $likes = json_decode($q1[0]['likes'], true);
                return $likes;
            }
        }
    }

    public function get_user($username)
    {
        $query = "select * from registration where username = '$username' limit 1";
        $q = $this->view($query);
        
        if($q)
        {
            return $q[0];
        }else{
            return false;
        }
    }

    public function fetch_post($postid){

        if(!is_numeric($postid)){
            return false;
        }
        $query = "select * from posts where postid = '$postid' limit 1";
        $q = $this->view($query);
        if($q)
        {
            return $q[0];
        }else{
            return false;
        }
    }

    public function delete_post($postid)
    {
        if(!is_numeric($postid)){
            return false;
        }
        $single_post = $this->fetch_post($postid);
        $q = "select parent from posts where postid = '$postid' limit 1";
        $result = $this->view($q);
        if (is_array($result)){
            print_r($result);
            if($result[0]['parent'] > 0){
                $parent = $result[0]['parent'];
    
                $sql = "update posts set comments = comments - 1 where postid = '$parent' limit 1";
                $this->conn->query($sql);
            }
        }
        $query = "delete from posts where postid = '$postid' limit 1";
        $this->conn->query($query);

        if ($single_post['picture'] != '' && file_exists($single_post['picture']))
        {
            unlink($single_post['picture']);
        }

        $query = "delete from posts where parent = '$postid'";
        $this->conn->query($query);
    }

    public function my_post($postid,$username)
    {
        if(!is_numeric($postid)){
            return false;
        }
        $query = "select * from posts where postid = '$postid' limit 1";
        $q = $this->view($query);

        if(is_array($q)){
            if($q[0]['username'] == $username){
                return true;
            }
        }
        return false;
    }

    public function like_counter($id, $type, $username){

        if($type == "post"){
            
            $query = "select likes from likescounter where type='post' && matterid = '$id' limit 1";
            $q1 = $this->view($query);
            if(is_array($q1)){


                $likes = json_decode($q1[0]['likes'], true);
                $user_name = array_column($likes, 'username');
                if(!in_array($username, $user_name)){
                    $lst["username"] = $username;
                    $lst["date"] = date("Y-m-d H:i:s");
                    $likes[] = $lst;
                    $likes_data = json_encode($likes);
                
                    $query = "update likescounter set likes = '$likes_data' where type='post' && matterid = '$id' limit 1";
                    $this->conn->query($query);
                    $query = "update posts set likes = likes + 1 where postid = '$id' limit 1";
                    $this->conn->query($query);
                }
                else{
                    $key = array_search($username, $user_name);
                    unset($likes[$key]);
                    $likes_data = json_encode($likes);
                    $query = "update likescounter set likes = '$likes_data' where type='post' && matterid = '$id' limit 1";
                    $this->conn->query($query);
                    $query = "update posts set likes = likes - 1 where postid = '$id' limit 1";
                    $this->conn->query($query);
                }
                
            }
            else{
                $lst["username"] = $username;
                $lst["date"] = date("Y-m-d H:i:s");

                $lst1[] = $lst;
                $likes = json_encode($lst1);
                $query = "insert into likescounter (type, matterid, likes) values ('$type', '$id', '$likes')";
                $this->conn->query($query);

                $query = "update posts set likes = likes + 1 where postid = '$id' limit 1";
                $this->conn->query($query);

            }

        }
        
    }
    public function change_post($data, $files)
    {
        if(!empty($data["post"]) || !empty($files['imagefile']['name']))
        {
            $error = "";
            $picture= "";
            $has_picture= 0;
            
            if(!empty($files['imagefile']['name']))
            {
                $uploaddir = '../my_images/' . $username . "/";
                if(!file_exists($uploaddir))
                {
                    mkdir($uploaddir,0777,true);
                }
                $picture = $uploaddir . basename($_FILES['imagefile']['name']);
                $result = move_uploaded_file($_FILES['imagefile']['tmp_name'], $picture);
                $has_picture= 1;
            }
            
            $post = "";
            if(isset($data['post'])){
                $post = addslashes($data['post']);
            }
            $postid = addslashes($data['postid']);
            if($has_picture){
                $query = "update posts set post = '$post', picture = '$picture' where postid = '$postid'";
            }else{
                $query = "update posts set post = '$post' where postid = '$postid'";
            }
            
            $q = $this->conn->query($query);
        }else{
            $error = "Please type something to post!<br>";
        }
        return $error;
    }

    public function change_info($data){
        $error = "";
        $phone = "";
        $dob = "";
        $address = "";
        $bio = "";
        if(!empty($data['phone']) || !empty($data['dob']) || !empty($data['address']) || !empty($data['bio'])){
            if(isset($data['phone'])){
                $phone = addslashes($data['phone']);
                print_r($phone);
            }
            if(isset($data['dob'])){
                $dob = addslashes($data['dob']);
            }
            if(isset($data['address'])){
                $address = addslashes($data['address']);
            }
            if(isset($data['bio'])){
                $bio = addslashes($data['bio']);
            }
            $query = "update registration set phone = '$phone', dob = '$dob', address = '$address', bio = '$bio' where username = '$_SESSION[username]'";
            
            $q = $this->conn->query($query);
        }else{
            $error = "Provide proper input.";
        }
    }

    public function check_login($username, $redirect = true)
    {
        if (!is_numeric($username)) {
            $query = "select * from registration where username = '$username' limit 1";

            $result = $this->view($query);
            if ($result) {
                $user_data = $result[0];
                return $user_data;
            } else {
                if ($redirect) {
                    header("Location: login.php");
                    die;
                } else {
                    $_SESSION['username'] = "";
                }
            }
        } else {
            if ($redirect) {
                header("Location: login.php");
                die;
            } else {
                $_SESSION['username'] = "";
            }
        }
    }
    
}

?>

