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

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $delpatientsqlstmt = $conn->prepare("DELETE FROM `patient` WHERE `patient_id`=?");
    $delpatientsqlstmt->bind_param("i", $_SESSION['patientidtemp']);
    $result = $delpatientsqlstmt->execute();
    unset($_SESSION['patientidtemp']);
    if($result){
        echo json_encode(array("success"=>true));
    }else{
        echo json_encode(array("success"=>false));
    }
}else{
    echo json_encode(array("success"=>false));
}