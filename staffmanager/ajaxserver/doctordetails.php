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

if (!isset($_GET['doctorid'])) {
    die();
}
$getdoctorsqlstmt = $conn->prepare("SELECT `doctor_id`, `name`, `phone_num`, `email`, `address`, `position` FROM `doctor` WHERE `doctor_id` = ?");
$getdoctorsqlstmt->bind_param("i", $_GET['doctorid']);
$getdoctorsqlstmt->execute();
$getdoctorsqlstmt->store_result();
if ($getdoctorsqlstmt->num_rows < 1) {
    die();
}
$getdoctorsqlstmt->bind_result($doctorid, $doctorname, $doctorphoneno, $doctoremail, $doctoraddr, $doctorpos);
$getdoctorsqlstmt->fetch();
$_SESSION['doctoridtemp'] = $doctorid;
?>
<div class="col-md-8">
    <label class="form-label">Doctor name</label>
    <input type="text" id="editname" value="<?php echo $doctorname ?>" class="form-control" required>
</div>
<div class="col-md-4">
    <label class="form-label">Position</label>
    <input type="text" id="editposition" value="<?php echo $doctorpos ?>" class="form-control" required>
</div>
<div class="col-md-8">
    <label class="form-label">Email</label>
    <input type="email" id="editemail" value="<?php echo $doctoremail ?>" class="form-control" required>
</div>
<div class="col-md-4">
    <label class="form-label">Phone number</label>
    <input type="text" id="editphoneno" value="<?php echo $doctorphoneno ?>" class="form-control" required>
</div>
<div class="col-md-12">
    <label class="form-label">Address</label>
    <input type="text" id="editaddr" value="<?php echo $doctoraddr ?>" class="form-control" required>
</div>
<br>
<button class="btn btn-danger btn-sm" type="button" style="width: 100%;" onclick="deletedoctor()">Delete Doctor</button>