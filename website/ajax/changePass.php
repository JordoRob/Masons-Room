<?php
session_start();
$check=array("access"=>2);
if(isset($_SESSION['logged'])){
    $check["access"]=1;
$user=$_SESSION['logged'];
include '../database.php';
	$oldP=$_POST['oldPass'];
    $newP=$_POST['newPass'];
	$query1 = "SELECT password FROM users WHERE user_id=?";
    $query2 = "UPDATE users SET password = ? WHERE user_id=?";
    $sql = $connection->prepare($query1);
    $sql->bind_param("s",$user);
                        $sql->execute();
                        $result1 = $sql->get_result();
                        $row= $result1->fetch_assoc();
                        if($row["password"]==$oldP){
                            $sql = $connection->prepare($query2);
                            $sql->bind_param("ss",$newP,$user);
                            $sql->execute();
                            $check["access"]=0;
                        }


}
echo json_encode($check);
mysqli_close($connection);



?>