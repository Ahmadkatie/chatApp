<?php
session_start();
if(isset($_SESSION['username'])){

if(isset($_POST['message'])&& isset($_POST['to_id'])){



include "../db_conn.php";
$message = $_POST['message'];
$to_id = $_POST['to_id'];

$from_id = $_SESSION['user_id'];

$sql = "INSERT INTO cahts (from_id,to_id,message)
                    VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $res=$stmt->execute([$from_id,$to_id,$message]);
    if($res){
        $sql2 = "SELECT * FROM conversation
        WHERE (user_1=? AND user_2=?)
        OR    (user_1=? AND user_2=?)";

    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute([$from_id,$to_id,$to_id,$from_id]);


    define('TIMEZONE', 'Asia/Damascus');
    date_default_timezone_set(TIMEZONE);
        $time = date("h:i:s a");
        if($stmt2->rowCount() == 0){
            $sql3 = "INSERT INTO conversation(user_1,user_2) VALUES(?,?) ";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->execute([$from_id,$to_id]);
        }?>

    <p class="rtext align-self-end border rounded p-2 mb-1">
        <?=$message?>
        <small class="d-block"><?=$time?></small>
    </p>

<?php
    }

}
}else{
    header("Location: ../../index.php");
    exit;
}
?>