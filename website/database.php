<?php
$server_name = "localhost";

//specify the username - here it is root
$user_name = "root";
$password = "";
    $database = "masonsroom";
    // Creating the connection by specifying the connection details
    $connection = new mysqli($server_name, $user_name, $password, $database);
?>