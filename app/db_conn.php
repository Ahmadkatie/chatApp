<?php
$dbServer="localhost";
$dbUser="root";
$pass="";
$dbName="chatApp";

#creating database connection
// try{
//     $conn = new PDO("mysql:host=$dbServer;dbname=$dbName,$dbUser,$pass");
//     $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
// }catch(PDOException $e){
//     echo "connection faild : ". $e->getMessage();
// }
try {
    $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "connection failed: " . $e->getMessage();
}
?>