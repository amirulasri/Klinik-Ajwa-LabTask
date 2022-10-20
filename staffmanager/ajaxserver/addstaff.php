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

if(isset($_POST['staffname']) && isset($_POST['staffposition']) && isset($_POST['staffaddr']) && isset($_POST['staffemail']) && isset($_POST['staffphoneno']) && isset($_POST['staffpassword'])){
    $addsqlstmt = $conn->prepare("INSERT INTO `staff`(`staff_id`, `name`, `phone_num`, `email`, `address`, `position`, `password`) VALUES (NULL,?,?,?,?,?,?)");
    $addsqlstmt->bind_param("ssssss", $_POST['staffname'], $_POST['staffphoneno'], $_POST['staffemail'], $_POST['staffaddr'], $_POST['staffposition'], $securedpass);
    $securedpass = password_hash($_POST['staffpassword'], PASSWORD_DEFAULT);
    $result = $addsqlstmt->execute();
    if($result){
        echo json_encode(array("success"=>true));
    }else{
        echo json_encode(array("success"=>false));
    }
}else{
    echo json_encode(array("success"=>false));
}