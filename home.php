<?php
session_start();
include 'app/db_conn.php';
if(isset($_SESSION['username'])){

include 'app/helpers/user.php';
include 'app/helpers/conversation.php';
include 'app/helpers/timeAgo.php';
include 'app/helpers/last_chat.php';
$user = getUser($_SESSION['username'],$conn);

$conversations = getConversation($user['user_id'],$conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="image/chat.png">
    <title>ChatApp - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="p-2 w-400 rownded shadow">
        <div class="">
            <div class="d-flex mb-3 p-3 bg-light justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="uploads/<?=$user['p_p']; ?>" class="w-25 rounded-circle">
                    <h3 class="fs-xs m-2"><?=$user['name'] ?></h3>
                </div>
                <a href="logout.php" class="btn btn-dark ">Logout</a>
            </div>
            <div class="input-group mb-3">
                <input type="text" placeholder="Search..." class="form-control" id="searchText">
                <button class="btn btn-primary" id="searchBtn">
                    <i class="fa fa-search" ></i>
                </button>
            </div>
            <ul class="list-group mvh-50 overflow-auto" id="chatList"> 
                <?php if(!empty($conversations)){ ?>
                    <?php foreach($conversations as $conv){ ?>
                <li class="list-group-item">
                    <a href="chat.php?user=<?=$conv['username']; ?>" class="d-flex justify-content-between align-items-center p-2">
                        <div class="d-flex align-items-center">
                            <img src="uploads/<?=$conv['p_p']; ?>" class="w-10 rounded">
                            <h3 class="fs-xs m-2"><?=$conv['name']; ?>
                                
                            </h3>
                        </div>
                        <?php if(last_seen($conv['last_seen'])=="Active"){?>
                        <div title="online">
                            <div class="online"></div>
                        </div>

                        <?php }?>
                    </a>
                    <small class="lastchat">
                                    <?php 
                                    echo lastChats($_SESSION['user_id'],$conv['user_id'],$conn);
                                    ?>
                    </small>
                </li>
                <?php  } ?>
                <?php  }else{  ?>
                    <div class="alert alert-info" >
                        <i class="fa fa-comments d-block fs-big"></i>
                        No message yet , Start the conversation
                    </div>
                <?php  } ?>
            </ul>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
$(document).ready(function(){



    // search 
$("#searchText").on("input",function(){
    var searchText = $(this).val();
    if (searchText == "") return;
    $.post('app/ajax/search.php',
    {
        key : searchText
    },
    function(data,status){
        $("#chatList").html(data);
    });
});
// search by button
$("#searchBtn").on("click",function(){
    var searchText = $("#searchText").val();
    if (searchText == "") return;
    $.post('app/ajax/search.php',
    {
        key : searchText
    },
    function(data,status){
        $("#chatList").html(data);
    });
});
/*auto update last seen for logged in user */
let lasSeenUpdate= function(){
$.get("app/ajax/upLastSeen.php")
}

lasSeenUpdate();
/*auto update last seen evry 10 sec */
setInterval(lasSeenUpdate,10000);

}) ;
</script>
</body>
</html>
<style>
    .fs-xs{
        font-size: 1rem;
        font-family: sans-serif;

    }
    .w-10{
    width: 10%;
}
a{
    text-decoration: none;
}
.fs-big{
    font-size: 5rem;
}
.online{
    width: 10px;
    height: 10px;
    background-color: rgba(23, 161, 23,.8);
    border-radius: 50%;
}
.lastchat{
    color: #494949;
    margin-left: 15%;
}
</style>
<?php
}else{
    header("Location: index.php");
    exit;
}
?>