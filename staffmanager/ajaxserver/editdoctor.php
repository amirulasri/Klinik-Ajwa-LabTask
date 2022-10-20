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

if (isset($_POST['doctorname']) && isset($_POST['doctorposition']) && isset($_POST['doctoraddr']) && isset($_POST['doctoremail']) && isset($_POST['doctorphoneno']) && isset($_SESSION['doctoridtemp'])) {
    $editsqlstmt = $conn->prepare("UPDATE `doctor` SET `name`=?,`phone_num`=?,`email`=?,`address`=?,`position`=? WHERE `doctor_id`=?");
    $editsqlstmt->bind_param("ssssss", $_POST['doctorname'], $_POST['doctorphoneno'], $_POST['doctoremail'], $_POST['doctoraddr'], $_POST['doctorposition'], $_SESSION['doctoridtemp']);
    $result = $editsqlstmt->execute();
    if ($result) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false));
    }
} else {
    echo json_encode(array("success" => false));
}
