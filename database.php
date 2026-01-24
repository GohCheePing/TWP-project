<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dentaldb"; 

$conn = new mysqli($servername, $username, $password, $dbname);

/*if ($conn){
   echo "You are connected";
}
else {
   echo "Connection failed";
} */


if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} 

?>