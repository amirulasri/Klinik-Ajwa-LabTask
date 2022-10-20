<?php
session_start();
include('../conn.php');
if (isset($_SESSION['staffid'])) {
    $staffid = $_SESSION['staffid'];
    $getstaffsqlstmt = $conn->prepare("SELECT `name`, `phone_num`, `email`, `address`, `position` FROM `staff` WHERE `staff_id` = ?");
    $getstaffsqlstmt->bind_param("s", $staffid);
    $getstaffsqlstmt->execute();
    $getstaffsqlstmt->store_result();
    if ($getstaffsqlstmt->num_rows < 1) {
        header('location: login');
        die();
    }
    $getstaffsqlstmt->bind_result($staffname, $staffphoneno, $staffemail, $staffaddress, $staffposition);
    $getstaffsqlstmt->fetch();
} else {
    header('location: login');
    die();
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
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Klinik Ajwa - Staff</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="patient">Patient</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>
    <div class="container text-center">
        <div class="row">
            <div class="col" id="patientlistcontent">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //GET PATIENT LIST CALL AJAX
        function getListPatient() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("patientlistcontent").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "ajaxserver/listpatientrecent.php", true);
            xmlhttp.send();
        }
        setTimeout(getListPatient, 200);
    </script>
</body>

</html>