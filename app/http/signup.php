<?php
# check if name, username, password submitted
if(isset($_POST['name']) &&
   isset($_POST['username']) && 
   isset($_POST['pass'])){

    # database connection file
    include '../db_conn.php';

    # get data and store in var
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['pass'];

    # making url data format
    $data = 'name='.$name.'&username='.$username;
    
    # simple form validation
    if(empty($name)){
        # error msg
        $em = "Name is required";

        # redirect to Signup page and passing error msg and data
        header("location: ../../signup.php?error=$em");
        exit;
    } elseif(empty($username)){
        $em = "Username is required";

        # redirect to Signup page and passing error msg and data
        header("location: ../../signup.php?error=$em&$data");
        exit;
    } elseif(empty($password)){
        $em = "Password is required";

        # redirect to Signup page and passing error msg and data
        header("location: ../../signup.php?error=$em&$data");
        exit;
    } else{
        # checking the database if username is taken
        $sql = "SELECT username FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        if($stmt->rowCount()>0){
            $em = "The username ($username) is taken";
            header("location: ../../signup.php?error=$em&$data");
            exit;
        } else{
            # Profile picture Uploading
            if(isset($_FILES['pp'])){
                $img_name = $_FILES['pp']['name'];
                $tmp_name = $_FILES['pp']['tmp_name'];
                $error = $_FILES['pp']['error'];

                # if there is no error occurred while uploading
                if($error == 0){
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    /* CONVERT THE IMAGE EXTENSION INTO LOWER CASE AND STORE IT IN VAR */
                    $img_ex_lc = strtolower($img_ex);

                    $allowed_exs = array("jpg", "png", "jpeg");
                    if(in_array($img_ex_lc, $allowed_exs)){
                        /* renaming the image with user's username like: username.$img_ex_lc */
                        $new_img_name = $username.'.'.$img_ex_lc;
                        # uploaded and move img to uploads folder
                        $img_upload_path = '../../uploads/' . $new_img_name;

                        if (!is_dir('../../uploads')) {
                            mkdir('../../uploads', 0777, true);
                        }
                        if(move_uploaded_file($tmp_name, $img_upload_path)){
                            // Image stored successfully
                        } else {
                            $em = "Unknown error occurred while uploading the image";
                            header("location: ../../signup.php?error=$em&$data");
                            exit;
                        }
                    } else{
                        $em = "You can't upload files of this type";
                        header("location: ../../signup.php?error=$em&$data");
                        exit;
                    }
                } else{
                    $em = "Unknown error occurred while uploading the image";
                    header("location: ../../signup.php?error=$em&$data");
                    exit;
                }
            }

            // hashing password
            $password = password_hash($password, PASSWORD_DEFAULT);
            
            if(isset($new_img_name)){
                // insert data into database
                $sql = "INSERT INTO users (name, username, password, p_p) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password, $new_img_name]);
            } else{
                // insert data into database
                $sql = "INSERT INTO users (name, username, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password]);
            }
            
            # success msg
            $sm = "Account created successfully";
            
            # redirect to 'index.php' and passing success msg
            header("location: ../../index.php?success=$sm");
            exit;   
        }
    }
} else{
    header("location: ../../signup.php");
    exit;
}
?>