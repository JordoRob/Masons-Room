<?php
$logged= array('access'=>false);
include '../database.php';
	$user=$_POST['user'];
	$email=$_POST['email'];
	$query1 = "SELECT password  FROM users WHERE username=? AND email=?";
    $sql1 = $connection->prepare($query1);
    $sql1->bind_param("ss",$user,$email);
                        $sql1->execute();
                        $result1 = $sql1->get_result();
                        if(mysqli_num_rows($result1)>0){
                            $row = $result1 -> fetch_assoc();
                            //SEND EMAIL HERE
                            $pass=$row['password'];


                            $logged = array('access' => true); 
                            
                            }else{


                            }
                        
echo json_encode($logged);
mysqli_close($connection);
?>