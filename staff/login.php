<?php
include('../conn.php');
session_start();
if (isset($_POST['staffid']) && isset($_POST['staffpass'])) {
    if (!empty($_POST['staffid']) && !empty($_POST['staffpass'])) {
        $getstaffsqlstmt = $conn->prepare("SELECT `staff_id`, `password` FROM `staff` WHERE `staff_id` = ?");
        $getstaffsqlstmt->bind_param("s", $_POST['staffid']);
        $getstaffsqlstmt->execute();
        $getstaffsqlstmt->store_result();
        if ($getstaffsqlstmt->num_rows < 1) {
            header('location: login?incorrect');
            die();
        }
        $getstaffsqlstmt->bind_result($staffid, $staffpass);
        $getstaffsqlstmt->fetch();
        if (password_verify($_POST['staffpass'], $staffpass)) {
            $_SESSION['staffid'] = $staffid;
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
        <h2 style="color: white; text-align: center;">Staff Login</h2> <p style="color: white; text-align: center;">Manage the patients</p><br>
        <div class="row">
            <div class="col-sm-4" style="margin-left: auto; margin-right: auto;">
                <?php
                if (isset($_GET['incorrect'])) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        Incorrect Staff ID or Password
                    </div>
                <?php } ?>
                <div class="loginstyle">
                    <form action="" method="POST">
                        <label for="" class="form-label" style="color: white;">Staff ID</label>
                        <input type="text" class="form-control" name="staffid" required><br>
                        <label for="" class="form-label" style="color: white;">Password</label>
                        <input type="password" class="form-control" name="staffpass" required><br>
                        <button type="submit" class="btn btn-primary" style="float: right;">Login</button>
                    </form>
                </div>
            </div>
        </div>
        <br><br>
    </div>
</body>

</html>