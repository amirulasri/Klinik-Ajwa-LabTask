<?php
include('../conn.php');
session_start();
if (isset($_POST['adminid']) && isset($_POST['adminpass'])) {
    if (!empty($_POST['adminid']) && !empty($_POST['adminpass'])) {
        $getadminsqlstmt = $conn->prepare("SELECT `admin_id`, `password` FROM `admin` WHERE `admin_id` = ?");
        $getadminsqlstmt->bind_param("s", $_POST['adminid']);
        $getadminsqlstmt->execute();
        $getadminsqlstmt->store_result();
        if ($getadminsqlstmt->num_rows < 1) {
            header('location: login?incorrect');
            die();
        }
        $getadminsqlstmt->bind_result($adminid, $adminpass);
        $getadminsqlstmt->fetch();
        if (password_verify($_POST['adminpass'], $adminpass)) {
            $_SESSION['adminid'] = $adminid;
            header('location: index');
        } else {
            header('location: login?incorrect');
            die();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../jquery.js"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Klinik Ajwa</title>
</head>

<body>
    <div class="container">
        <br><br><br>
        <h2 style="color: white; text-align: center;">Admin Login</h2> <p style="color: white; text-align: center;">Manage the staffs and doctors</p><br>
        <div class="row">
            <div class="col-sm-4" style="margin-left: auto; margin-right: auto;">
                <?php
                if (isset($_GET['incorrect'])) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        Incorrect Admin ID or Password
                    </div>
                <?php } ?>
                <div class="loginstyle">
                    <form action="" method="POST">
                        <label for="" class="form-label" style="color: white;">Admin ID</label>
                        <input type="text" class="form-control" name="adminid" required><br>
                        <label for="" class="form-label" style="color: white;">Password</label>
                        <input type="password" class="form-control" name="adminpass" required><br>
                        <button type="submit" class="btn btn-primary" style="float: right;">Login</button>
                    </form>
                </div>
            </div>
        </div>
        <br><br>
    </div>
</body>

</html>