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

if (!isset($_GET['staffid'])) {
    die();
}
$getstaffsqlstmt = $conn->prepare("SELECT `staff_id`, `name`, `phone_num`, `email`, `address`, `position` FROM `staff` WHERE `staff_id` = ?");
$getstaffsqlstmt->bind_param("i", $_GET['staffid']);
$getstaffsqlstmt->execute();
$getstaffsqlstmt->store_result();
if ($getstaffsqlstmt->num_rows < 1) {
    die();
}
$getstaffsqlstmt->bind_result($staffid, $staffname, $staffphoneno, $staffemail, $staffaddr, $staffpos);
$getstaffsqlstmt->fetch();
$_SESSION['staffidtemp'] = $staffid;
?>
<div class="col-md-8">
    <label class="form-label">Staff name</label>
    <input type="text" id="editname" value="<?php echo $staffname ?>" class="form-control" required>
</div>
<div class="col-md-4">
    <label class="form-label">Position</label>
    <input type="text" id="editposition" value="<?php echo $staffpos ?>" class="form-control" required>
</div>
<div class="col-md-5">
    <label class="form-label">Email</label>
    <input type="email" id="editemail" value="<?php echo $staffemail ?>" class="form-control" required>
</div>
<div class="col-md-3">
    <label class="form-label">Phone number</label>
    <input type="text" id="editphoneno" value="<?php echo $staffphoneno ?>" class="form-control" required>
</div>
<div class="col-md-4">
    <label class="form-label">Password</label>
    <input type="password" id="editpassword" placeholder="Leave blank to keep old" class="form-control">
</div>
<div class="col-md-12">
    <label class="form-label">Address</label>
    <input type="text" id="editaddr" value="<?php echo $staffaddr ?>" class="form-control" required>
</div>
<br>
<button class="btn btn-danger btn-sm" type="button" style="width: 100%;" onclick="deletestaff()">Delete Staff</button>