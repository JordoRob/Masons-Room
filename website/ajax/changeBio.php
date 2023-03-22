<?php
session_start();
$check=array("access"=>1);

$user=$_SESSION['logged'];
include '../database.php';
    $newBio=$_POST['newBio'];
    $query = "UPDATE users SET user_bio = ? WHERE user_id=?";
    $sql = $connection->prepare($query);
    $sql->bind_param("ss",$newBio,$user);
                        $sql->execute();
                        $check["access"]=0;

echo json_encode($check);
mysqli_close($connection);



?>