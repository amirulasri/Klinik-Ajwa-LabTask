<?php
session_start();
include('../../conn.php');
if (isset($_SESSION['staffid'])) {
    $staffid = $_SESSION['staffid'];
    $getstaffsqlstmt = $conn->prepare("SELECT `name`, `phone_num`, `email`, `address`, `position` FROM `staff` WHERE `staff_id` = ?");
    $getstaffsqlstmt->bind_param("s", $staffid);
    $getstaffsqlstmt->execute();
    $getstaffsqlstmt->store_result();
    if ($getstaffsqlstmt->num_rows < 1) {
        echo json_encode(array("success"=>false));
        die();
    }
    $getstaffsqlstmt->bind_result($staffname, $staffphoneno, $staffemail, $staffaddress, $staffposition);
    $getstaffsqlstmt->fetch();
} else {
    echo json_encode(array("success"=>false));
    die();
}

if(isset($_POST['patientname']) && isset($_POST['patientic']) && isset($_POST['patientaddr']) && isset($_POST['patientphoneno']) && isset($_POST['patientrecord']) && isset($_SESSION['patientidtemp'])){
    $editpatientsqlstmt = $conn->prepare("UPDATE `patient` SET `name`=?,`ic`=?,`phone_number`=?,`address`=?,`record`=? WHERE `patient_id`=?");
    $editpatientsqlstmt->bind_param("sssssi", $_POST['patientname'], $_POST['patientic'], $_POST['patientphoneno'], $_POST['patientaddr'], $_POST['patientrecord'], $_SESSION['patientidtemp']);
    $result = $editpatientsqlstmt->execute();
    unset($_SESSION['patientidtemp']);
    if($result){
        echo json_encode(array("success"=>true));
    }else{
        echo json_encode(array("success"=>false));
    }
}else{
    echo json_encode(array("success"=>false));
}