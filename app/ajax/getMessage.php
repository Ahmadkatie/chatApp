<?php
session_start();
if(isset($_SESSION['username'])){

if(isset($_POST['id_2'])){

include "../db_conn.php";

$id_1 =$_SESSION['user_id'];
$id_2 = $_POST['id_2'];
$opended = 0;
$sql = "SELECT * FROM cahts WHERE to_id =?
                            AND from_id = ?
                            ORDER BY chat_id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_1,$id_2]);

if($stmt->rowCount() > 0){
    $chats = $stmt ->fetchAll();

    foreach($chats as $chat){
        if ($chat['opended'] == 0){
            $opended =1;
            $chat_id = $chat['chat_id'];

            $sql2 = "UPDATE cahts SET opended = ?
            WHERE chat_id = ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute([$opended,$chat_id]);
            ?>
                <p class="ltext border rounded p-2 mb-1">
                <?=$chat['message']?>
                    <small class="d-block"><?=$chat['created_at']?></small>
                </p>
<?php
        }
    }
}


}
}else{
    header("Location: ../../index.php");
    exit;
}

?>