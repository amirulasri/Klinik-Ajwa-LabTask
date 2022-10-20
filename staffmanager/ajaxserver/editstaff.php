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

if(isset($_POST['staffname']) && isset($_POST['staffposition']) && isset($_POST['staffaddr']) && isset($_POST['staffemail']) && isset($_POST['staffphoneno']) && isset($_SESSION['staffidtemp'])){
    if(isset($_POST['staffpassword']) && !empty($_POST['staffpassword'])){
        $editsqlstmt = $conn->prepare("UPDATE `staff` SET `name`=?,`phone_num`=?,`email`=?,`address`=?,`position`=?,`password`=? WHERE `staff_id`=?");
        $editsqlstmt->bind_param("ssssssi", $_POST['staffname'], $_POST['staffphoneno'], $_POST['staffemail'], $_POST['staffaddr'], $_POST['staffposition'], $securedpass, $_SESSION['staffidtemp']);
        $securedpass = password_hash($_POST['staffpassword'], PASSWORD_DEFAULT);
    }else{
        $editsqlstmt = $conn->prepare("UPDATE `staff` SET `name`=?,`phone_num`=?,`email`=?,`address`=?,`position`=? WHERE `staff_id`=?");
        $editsqlstmt->bind_param("ssssss", $_POST['staffname'], $_POST['staffphoneno'], $_POST['staffemail'], $_POST['staffaddr'], $_POST['staffposition'], $_SESSION['staffidtemp']);
    }
    $result = $editsqlstmt->execute();
    if($result){
        echo json_encode(array("success"=>true));
    }else{
        echo json_encode(array("success"=>false));
    }
}else{
    echo json_encode(array("success"=>false));
}