<?php
session_start();
include "../database.php";
if(isset($_SESSION['logged'])){
$heroid=$_SESSION['logged'];
$villainid=$_POST['villainid'];
$focus=$_POST['focus'];
$focusid=$_POST['focusid'];
$report=$_POST['report'];
if(strcmp($focus, "account")==0){
    $query="INSERT INTO reports(hero_id,villain_id,focus,report) VALUES (?,?,?,?)";
    $sql = $connection->prepare($query);
        $sql->bind_param("iiss",$heroid,$villainid,$focus,$report);
}
if(strcmp($focus, "post")==0){
    $query="INSERT INTO reports(hero_id,villain_id,focus,report,post_id) VALUES (?,?,?,?,?)";
    $sql = $connection->prepare($query);
    $sql->bind_param("iissi",$heroid,$villainid,$focus,$report,$focusid);
}else if(strcmp($focus, "reply")==0){
$query="INSERT INTO reports(hero_id,villain_id,focus,report,reply_id) VALUES (?,?,?,?,?)";
    $sql = $connection->prepare($query);
    $sql->bind_param("iissi",$heroid,$villainid,$focus,$report,$focusid);}

    if($sql->execute()){
    
echo json_encode(array("submitted"=>true));}
}else
echo json_encode(array("submitted"=>false));
?>