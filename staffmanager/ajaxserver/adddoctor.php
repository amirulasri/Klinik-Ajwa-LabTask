<?php
include('../../conn.php');
session_start();
if (isset($_SESSION['adminid'])) {
    $adminid = $_SESSION['adminid'];
    $getadminsqlstmt = $conn->prepare("SELECT `admin_id` FROM `admin` WHERE `admin_id` = ?");
    $getadminsqlstmt->bind_param("s", $adminid);
    $getadminsqlstmt->execute();
    $getadminsqlstmt->store_result();
    if ($getadminsqlstmt->num_rows < 1) {
        header('location: login');
        die();
    }
    $getadminsqlstmt->bind_result($adminid);
    $getadminsqlstmt->fetch();
} else {
    header('location: login');
    die();
}

if(isset($_POST['doctorname']) && isset($_POST['doctorposition']) && isset($_POST['doctoraddr']) && isset($_POST['doctoremail']) && isset($_POST['doctorphoneno'])){
    $addsqlstmt = $conn->prepare("INSERT INTO `doctor`(`doctor_id`, `name`, `phone_num`, `email`, `address`, `position`) VALUES (NULL,?,?,?,?,?)");
    $addsqlstmt->bind_param("sssss", $_POST['doctorname'], $_POST['doctorphoneno'], $_POST['doctoremail'], $_POST['doctoraddr'], $_POST['doctorposition']);
    $result = $addsqlstmt->execute();
    if($result){
        echo json_encode(array("success"=>true));
    }else{
        echo json_encode(array("success"=>false));
    }
}else{
    echo json_encode(array("success"=>false));
}