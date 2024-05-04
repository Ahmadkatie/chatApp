<?php
session_start();
# check if name, username, password submitted
if (isset($_POST['username']) && isset($_POST['password'])) {

    # database connection file
    include '../db_conn.php';

    # get data and store in var
    $username = $_POST['username'];
    $password = $_POST['password'];



    #simple form validations
    if (empty($username)) {
        $em = "Username is required";

        # redirect to index page and passing error msg and data
        header("location: ../../index.php?error=$em");
    } elseif (empty($password)) {
        $em = "Password is required";

        # redirect to index page and passing error msg and data
        header("location: ../../index.php?error=$em");
    } else {
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch();
            if ($user['username'] == $username) {
                if(password_verify($password,$user['password'])){
                    #successfuly Login
                    #creating session
                    $_SESSION['username']= $user['username'];
                    $_SESSION['name']= $user['name'];
                    $_SESSION['user_id']= $user['user_id'];

                    header("location: ../../home.php");


                }else{
                    $em = "Incorect Username Or Password";

                    # redirect to index page and passing error msg and data
                    header("location: ../../index.php?error=$em");
                }
            }else{
                $em = "Incorect Username Or Password";

                # redirect to index page and passing error msg and data
                header("location: ../../index.php?error=$em");
            }
        }else {
            $em = "Incorect Username Or Password";
            # redirect to index page and passing error msg and data
            header("location: ../../index.php?error=$em");
        }
    }
} else {
    header("location: ../../index.php");
    exit;
}
