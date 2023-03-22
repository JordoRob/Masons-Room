<?php
session_start();
include "../database.php";
if(isset($_SESSION['logged'])){
    $user = $_SESSION['logged'];
    $body = $_POST['body'];
    $title = $_POST['title'];
    $topic = $_POST['topic'];
    $tag = $_POST['tag'];
    $query = "INSERT into posts(topic_id,user_id,content,post_title,tag) VALUES (?, ?, ?, ?, ?)";
    $sql = $connection->prepare($query);
    $sql->bind_param("iisss",$topic, $user,$body,$title,$tag);
    $sql->execute();
    $newpost=$sql->insert_id;
    $array =array("id"=> $newpost,
"topic"=>$topic);
    echo json_encode($array);
}
else
    echo json_encode(-1);
    mysqli_close($connection);
?>