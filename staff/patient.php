<?php
session_start();
include('../conn.php');
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../jquery.js"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Klinik Ajwa</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Klinik Ajwa - Staff</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Patient</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>
    <div class="container">
        <!-- TOAST -->
        <div class="toast-container position-fixed top-0 start-50 translate-middle-x">
            <br><br><br>
            <div id="liveToast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="alertmsg">
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modaladdpatient">Add Patient</button>
                <button type="button" class="btn btn-info btn-sm" onclick="reloadListPatientWithSpinner()">Reload Patient</button>
                <div style="display: inline;" id="loaderlistpatient">
                </div>
                <br><br>
            </div>
        </div>
        <div class="row">
            <div class="col" id="patientlistcontent">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Patient-->
    <div class="modal fade" id="modaladdpatient" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Patient</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" id="addpatientform">
                        <div class="col-md-8">
                            <label class="form-label">Patient name</label>
                            <input type="text" id="addpatientname" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IC Number</label>
                            <input type="text" id="addpatientic" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" id="addpatientaddr" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone number</label>
                            <input type="text" id="addpatientphoneno" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Record</label>
                            <input type="text" id="addpatientrecord" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" form="addpatientform" value="Add Patient" class="btn btn-primary">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View/Modify Patient-->
    <div class="modal fade" id="modalpatientdetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Patient Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" id="editpatientform">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" form="editpatientform" value="Update Patient" class="btn btn-primary">
                </div>
            </div>
        </div>
    </div>

    <script>
        function alertmessage(msgtext) {
            document.getElementById("alertmsg").innerHTML = msgtext;
            const toastLive = document.getElementById('liveToast')
            const toast = new bootstrap.Toast(toastLive)
            toast.show()
        }

        //ADD PATIENT
        function addpatient() {
            var patientname = document.getElementById("addpatientname").value;
            var patientic = document.getElementById("addpatientic").value;
            var patientaddr = document.getElementById("addpatientaddr").value;
            var patientphoneno = document.getElementById("addpatientphoneno").value;
            var patientrecord = document.getElementById("addpatientrecord").value;

            $.post("ajaxserver/addpatient.php", {
                patientname: patientname,
                patientic: patientic,
                patientaddr: patientaddr,
                patientphoneno: patientphoneno,
                patientrecord: patientrecord,

            }, function(data, status) {
                var jsondata = JSON.parse(data);
                if (jsondata['success']) {
                    getListPatient();
                    $('#modaladdpatient').modal('hide');
                    document.getElementById("addpatientform").reset();
                    alertmessage("Successfully add patient");
                } else {
                    alertmessage("Failed to add patient");
                }
            });
        }

        //EDIT PATIENT
        function editpatient() {
            var patientname = document.getElementById("editpatientname").value;
            var patientic = document.getElementById("editpatientic").value;
            var patientaddr = document.getElementById("editpatientaddr").value;
            var patientphoneno = document.getElementById("editpatientphoneno").value;
            var patientrecord = document.getElementById("editpatientrecord").value;

            $.post("ajaxserver/editpatient.php", {
                patientname: patientname,
                patientic: patientic,
                patientaddr: patientaddr,
                patientphoneno: patientphoneno,
                patientrecord: patientrecord,

            }, function(data, status) {
                var jsondata = JSON.parse(data);
                if (jsondata['success']) {
                    getListPatient();
                    $('#modalpatientdetails').modal('hide');
                    alertmessage("Successfully edit patient");
                } else {
                    alertmessage("Failed to edit patient");
                }
            });
        }

        function deletepatient() {
            $.post("ajaxserver/deletepatient.php", {}, function(data, status) {
                var jsondata = JSON.parse(data);
                if (jsondata['success']) {
                    getListPatient();
                    $('#modalpatientdetails').modal('hide');
                    alertmessage("Successfully delete patient");
                } else {
                    alertmessage("Failed to delete patient");
                }
            });
        }

        //GET PATIENT LIST CALL AJAX
        function getListPatient() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("patientlistcontent").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "ajaxserver/listpatient.php", true);
            xmlhttp.send();
        }

        function delay(time) {
            return new Promise(resolve => setTimeout(resolve, time));
        }

        function reloadListPatientWithSpinner() {
            document.getElementById("loaderlistpatient").innerHTML = '<div class="spinner-border text-light spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
            getListPatient();
            delay(1000).then(() => {
                document.getElementById("loaderlistpatient").innerHTML = '';
                alertmessage('Reload list successfully');
            });
        }

        //GET PATIENT DETAILS CALL AJAX
        function getPatientDetails(patientid) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("editpatientform").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "ajaxserver/patientdetails.php?patientid=" + patientid, true);
            xmlhttp.send();
        }

        setTimeout(getListPatient, 200);

        //FORM VALIDATOR
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    } else {
                        event.preventDefault()
                        event.stopPropagation()
                        if (form.id == 'addpatientform') {
                            addpatient();
                        } else if (form.id == 'editpatientform') {
                            editpatient();
                        }
                    }
                }, false)
            })
        })()
    </script>
</body>

</html>