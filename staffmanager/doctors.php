<?php
session_start();
include('../conn.php');
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
            <a class="navbar-brand" href="#">Klinik Ajwa - Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index">Staffs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Doctors</a>
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
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modaladd">Add Doctor</button>
                <button type="button" class="btn btn-info btn-sm" onclick="reloadListWithSpinner()">Reload Doctor</button>
                <div style="display: inline;" id="loaderlist">
                </div>
                <br><br>
            </div>
        </div>
        <div class="row">
            <div class="col" id="listcontent">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Doctor-->
    <div class="modal fade" id="modaladd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Doctor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" id="addform">
                        <div class="col-md-8">
                            <label class="form-label">Doctor name</label>
                            <input type="text" id="addname" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Position</label>
                            <input type="text" id="addposition" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Email</label>
                            <input type="email" id="addemail" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone number</label>
                            <input type="text" id="addphoneno" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <input type="text" id="addaddr" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" form="addform" value="Add Doctor" class="btn btn-primary">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View/Modify Doctor-->
    <div class="modal fade" id="modaledit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Doctor details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" id="editform">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" form="editform" value="Modify Doctor" class="btn btn-primary">
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

        //GET LIST CALL AJAX
        function getListDoctor() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("listcontent").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "ajaxserver/listdoctor.php", true);
            xmlhttp.send();
        }
        setTimeout(getListDoctor, 200);

        //ADD DOCTOR
        function adddoctor() {
            var name = document.getElementById("addname").value;
            var position = document.getElementById("addposition").value;
            var addr = document.getElementById("addaddr").value;
            var email = document.getElementById("addemail").value;
            var phoneno = document.getElementById("addphoneno").value;

            $.post("ajaxserver/adddoctor.php", {
                doctorname: name,
                doctorposition: position,
                doctoraddr: addr,
                doctoremail: email,
                doctorphoneno: phoneno

            }, function(data, status) {
                var jsondata = JSON.parse(data);
                if (jsondata['success']) {
                    getListDoctor();
                    $('#modaladd').modal('hide');
                    document.getElementById("addform").reset();
                    alertmessage('Successfully add doctor');
                } else {
                    alertmessage('Failed to add doctor');
                }
            });
        }

        //EDIT DOCTOR
        function editdoctor() {
            var name = document.getElementById("editname").value;
            var position = document.getElementById("editposition").value;
            var addr = document.getElementById("editaddr").value;
            var email = document.getElementById("editemail").value;
            var phoneno = document.getElementById("editphoneno").value;

            $.post("ajaxserver/editdoctor.php", {
                doctorname: name,
                doctorposition: position,
                doctoraddr: addr,
                doctoremail: email,
                doctorphoneno: phoneno

            }, function(data, status) {
                var jsondata = JSON.parse(data);
                if (jsondata['success']) {
                    getListDoctor();
                    $('#modaledit').modal('hide');
                    alertmessage('Successfully edit doctor');
                } else {
                    alertmessage('Failed to edit doctor');
                }
            });
        }

        function deletedoctor() {
            $.post("ajaxserver/deletedoctor.php", {}, function(data, status) {
                var jsondata = JSON.parse(data);
                if (jsondata['success']) {
                    getListDoctor();
                    $('#modaledit').modal('hide');
                    alertmessage('Successfully delete doctor');
                } else {
                    alertmessage('Failed to delete doctor');
                }
            });
        }

        function delay(time) {
            return new Promise(resolve => setTimeout(resolve, time));
        }
        function reloadListWithSpinner() {
            document.getElementById("loaderlist").innerHTML = '<div class="spinner-border text-light spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
            getListDoctor();
            delay(1000).then(() => {
                document.getElementById("loaderlist").innerHTML = '';
                alertmessage('Reload list successfully');
            });
        }

        //GET DOCTOR DETAILS CALL AJAX
        function getDoctorDetails(doctorid) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("editform").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "ajaxserver/doctordetails.php?doctorid=" + doctorid, true);
            xmlhttp.send();
        }

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
                        if (form.id == 'addform') {
                            adddoctor();
                        } else if (form.id == 'editform') {
                            editdoctor();
                        }
                    }
                }, false)
            })
        })()
    </script>
</body>

</html>