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
?>
<table class="table">
    <thead class="table-dark">
        <tr>
            <th colspan="4">Patient</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone No</th>
            <th></th>
        </tr>
    </thead>
    <tbody style="color: white;">
        <?php
        $getpatientsql = "SELECT `patient_id`, `name`, `ic`, `phone_number`, `address`, `record` FROM `patient`";
        $getpatientresult = $conn->query($getpatientsql);
        if ($getpatientresult->num_rows > 0) {
            while ($patientdata = $getpatientresult->fetch_array()) {
        ?>
                <tr>
                    <td><?php echo $patientdata[0] ?></td>
                    <td><?php echo $patientdata[1] ?></td>
                    <td><?php echo $patientdata[3] ?></td>
                    <td><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalpatientdetails" onclick="getPatientDetails('<?php echo $patientdata[0] ?>')" style="float: right;">View</button></td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="4">No record</td>
            </tr>
        <?php } ?>
    </tbody>
</table>