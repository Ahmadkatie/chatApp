<?php
function getConversation($user_id,$conn){
    /* Getting all the conversations for current (logged in ) user */
    $sql = "SELECT * FROM conversation WHERE  user_1 =? OR user_2 =? 
    ORDER BY conversation_id DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id,$user_id]);
    if($stmt->rowCount()>0){
        $conversation = $stmt->fetchAll();
        /* Creating empty array to store the user conversation */
        $user_data = [];

        # looping through the conversation
        foreach($conversation as $conv){
            # if conversation user_1 row equal to user_id
            if($conv['user_1'] == $user_id){
                $sql2 = "SELECT user_id, name,username , p_p ,last_seen FROM users WHERE user_id=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$conv['user_2']]);     
            }else{
                $sql2 = "SELECT user_id, name,username , p_p ,last_seen FROM users WHERE user_id=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$conv['user_1']]); 
            }

            $allConversations = $stmt2->fetchAll();

            #push the data into the array
            array_push($user_data,$allConversations[0]);
        }

        return $user_data;
    }else{
        $conversation = [];
        return $conversation;

    }
}

?>