<?php
include('../../conn.php');
session_start();
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

if (!isset($_GET['patientid'])) {
    die();
}
$getpatientsqlstmt = $conn->prepare("SELECT `patient_id`, `name`, `ic`, `phone_number`, `address`, `record` FROM `patient` WHERE `patient_id` = ?");
$getpatientsqlstmt->bind_param("i", $_GET['patientid']);
$getpatientsqlstmt->execute();
$getpatientsqlstmt->store_result();
if ($getpatientsqlstmt->num_rows < 1) {
    die();
}
$getpatientsqlstmt->bind_result($patientid, $patientname, $patientic, $patientphoneno, $patientaddr, $patientrecord);
$getpatientsqlstmt->fetch();
$_SESSION['patientidtemp'] = $patientid;
?>
<div class="col-md-8">
    <label class="form-label">Patient name</label>
    <input type="text" id="editpatientname" value="<?php echo $patientname ?>" class="form-control" required>
</div>
<div class="col-md-4">
    <label class="form-label">IC Number</label>
    <input type="text" id="editpatientic" value="<?php echo $patientic ?>" class="form-control" required>
</div>
<div class="col-md-6">
    <label class="form-label">Address</label>
    <input type="text" id="editpatientaddr" value="<?php echo $patientaddr ?>" class="form-control" required>
</div>
<div class="col-md-3">
    <label class="form-label">Phone number</label>
    <input type="text" id="editpatientphoneno" value="<?php echo $patientphoneno ?>" class="form-control" required>
</div>
<div class="col-md-3">
    <label class="form-label">Record</label>
    <input type="text" id="editpatientrecord" value="<?php echo $patientrecord ?>" class="form-control" required>
</div>
<br>
<button class="btn btn-danger btn-sm" type="button" style="width: 100%;" onclick="deletepatient()">Delete Patient</button>