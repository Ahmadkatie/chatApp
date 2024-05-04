<?php
session_start();
if(isset($_SESSION['username'])){
include 'app/db_conn.php';
include 'app/helpers/user.php';
include 'app/helpers/chat.php';

include 'app/helpers/timeAgo.php';

if(!isset($_GET['user'])){
    header("Location: home.php");
    exit;
}

$chatWith = getUser($_GET['user'],$conn);
if(empty($chatWith)){
    header("Location: home.php");
    exit;
}


$chats = getChats($_SESSION['user_id'],$chatWith['user_id'],$conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="image/chat.png">
    <title>Chat App </title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
<div class="w-400 shadow p-4 rounded">
    <a href="home.php" class="fs-2 link-dark">&#8592</a> 
    <div class="d-flex align-items-center">
        <img src="uploads/<?=$chatWith['p_p']?>" class="w-15 rounded-circle">
        <h3 class="display-3 fs-sm m-2 text-success fw-medium">
            <?=$chatWith['name']?>
            <br>
            <div class="d-flex align-items-center" title="online">
            <?php
                    if(last_seen($chatWith['last_seen'])=="Active"){
                    ?>    
                <div class="online"></div>
                <small class="d-block p-1">Online</small>
                <?php }else{?>
                <small class="d-block p-1">
                    Last Seen:
                    <?=last_seen($chatWith['last_seen']) ?>
                </small>
                    <?php }?>
            </div>
        </h3>
    </div>
    <div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
    <?php
        if(!empty($chats)){
            foreach($chats as $chat){
                if($chat['from_id'] == $_SESSION['user_id']){?>
            <p class="rtext align-self-end border rounded p-2 mb-1">
                <?=$chat['message']?>
                <small class="d-block"><?=$chat['created_at']?></small>
            </p>
<?php }else{ ?>
    <p class="ltext border rounded p-2 mb-1">
    <?=$chat['message']?>
        <small class="d-block"><?=$chat['created_at']?></small>
    </p>
 <?php  }
            }}else{?>
        
        <div class="alert alert-info" >
            <i class="fa fa-comments d-block fs-big"></i>
            No message yet , Start the conversation
        </div>

    <?php }?>
</div>
    <div class="input-group mb-3">
        <textarea  cols="3" class="form-control" id="message"></textarea>
        <button class="btn btn-success" id="sendBtn">
            <i class="fa fa-paper-plane"></i>
        </button>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    var scrollDown = function(){
        let chatBox = document.getElementById('chatBox');
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    
$(document).ready(function(){
    $("#sendBtn").on('click',function(){
        message = $("#message").val();
        if(message == "")return;
        $.post("app/ajax/insert.php",
    {
        message: message,
        to_id: <?=$chatWith['user_id']?>
    },
        function(data,status){
            $("#message").val("");
            $("#chatBox").append(data);
            scrollDown();
        });
    });
    let lasSeenUpdate= function(){
$.get("app/ajax/upLastSeen.php")
}

lasSeenUpdate();
/*auto update last seen evry 10 sec */
setInterval(lasSeenUpdate,10000);


// auto refresh / reload
let fechData = function(){
    $.post("app/ajax/getMessage.php",
        {
            id_2: <?=$chatWith['user_id']?>
        },
        function(data,status){
                    $("#chatBox").append(data);
                    if(data != "") scrollDown();
        });
}
fechData();
/*auto update last seen evry .5 sec */
setInterval(fechData,500);


});
</script>
</body>

</html>
<style>
    .fs-big{
    font-size: 5rem;
}
    .w-15{
    width: 15%;
}
.fs-sm{ 
    font-size: 1.4rem;
}
.online{
    width: 10px;
    height: 10px;
    background-color: rgba(23, 161, 23,.8);
    border-radius: 50%;
    margin-right: 5px;
}
small{
    color: #bbb;
    font-size: 0.7rem;
    font-weight: bolder;
    text-align: right;
}
.chat-box{
    overflow-y: auto;
    max-height: 50vh;
}
.rtext{
    width: 65%;
    background: #f8f9fa;
    color: #444;
}
.ltext{
    width: 65%;
    background: #3289c8;
    color: #fff;
}
*::-webkit-scrollbar{
    width: 5px;
    
}
*::-webkit-scrollbar-track{
    background: #f1f1f1;
}
*::-webkit-scrollbar-thumb{
    background: #aaa;
    border-radius: 10px;
}
*::-webkit-scrollbar-thumb:hover{
    background: #198754;
}
.fa-paper-plane{
    padding: 0 10px;
}
textarea{
    resize: none;
}

textarea.form-control:focus{
    box-shadow:  0 0px 5px 2px #198754;

}
</style>
<?php
}else{
    header("Location: index.php");
    exit;
}
?>
