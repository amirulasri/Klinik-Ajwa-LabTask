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
?>
<table class="table">
    <thead class="table-dark">
        <tr>
            <th colspan="4">Staffs</th>
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
        $getstaffsql = "SELECT `staff_id`, `name`, `phone_num`, `email`, `address`, `position` FROM `staff`";
        $getstaffresult = $conn->query($getstaffsql);
        if ($getstaffresult->num_rows > 0) {
            while ($staffdata = $getstaffresult->fetch_array()) {
        ?>
                <tr>
                    <td><?php echo $staffdata[0] ?></td>
                    <td><?php echo $staffdata[1] ?></td>
                    <td><?php echo $staffdata[2] ?></td>
                    <td><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modaledit" onclick="getStaffDetails('<?php echo $staffdata[0] ?>')" style="float: right;">View</button></td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="4">No record</td>
            </tr>
        <?php } ?>
    </tbody>
</table>