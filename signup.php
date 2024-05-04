<?php
session_start();
if(!isset($_SESSION['username'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="image/chat.png">
    <title>ChatApp - Sign Up</title>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="w-400 p-5 shadow rounded">
        <form method="post" action="app/http/signup.php" enctype="multipart/form-data">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <img src="image/chat-53.png" class="w-30">
                <h3 class="display-4 fs-1 text-center text-primary  fw-semibold">Sign Up</h3>
            </div>
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php }
            if (isset($_GET['username'])) {
                $username = $_GET['username'];
            } else $username = '';
            if (isset($_GET['name'])) {
                $name = $_GET['name'];
            } else $name = '';
            
            ?>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" value="<?php echo $name ?>" name="name">
            </div>
            <div class="mb-3">
                <label class="form-label">User Name</label>
                <input type="text" class="form-control" value="<?php echo $username ?>" name="username">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="pass">
            </div>
            <div class="mb-3">
                <label class="form-label">Profile Picture</label>
                <input type="file" class="form-control" name="pp">
            </div>
            <button type="submit" class="btn btn-primary ">Sign Up</button>
            <span>Do You have Account ? <a href="index.php" class="fw-semibold">Log IN</a></span>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
<?php
}else{
    header("Location: home.php");
    exit;
}
?>