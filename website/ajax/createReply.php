<?php
session_start();
include "../database.php";
if(isset($_SESSION['logged'])){
    $user = $_SESSION['logged'];
    $content = $_POST['content'];
    $parentid = $_POST['parentid'];
    if($parentid=="null"||$parentid==0){$parentid=NULL;}
    $postid = $_POST['postid'];
    $query = "INSERT into replies(user_id,content,post_id,parent_id) VALUES (?, ?, ?, ?)";
    $sql = $connection->prepare($query);
    $sql->bind_param("isii",$user, $content,$postid,$parentid);
    $sql->execute();}
    mysqli_close($connection);
?>