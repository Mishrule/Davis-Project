<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    $id = intval($_GET['id']);

    $msg = ''; // Initialize a message variable

    if (isset($_POST['submit'])) {
        $studentNumber = $_POST['studentNumber'];
        $academicYear = $_POST['academicYear'];
        $term = $_POST['term'];
        $billamount = $_POST['billamount'];
        $studentClass = $_POST['studentClass'];

        $sql = "UPDATE tblbills SET studentNumber=:studentNumber, academicYear=:academicYear, term=:term, billamount=:billamount, studentClass=:studentClass WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentNumber', $studentNumber, PDO::PARAM_STR);
        $query->bindParam(':academicYear', $academicYear, PDO::PARAM_STR);
        $query->bindParam(':term', $term, PDO::PARAM_STR);
        $query->bindParam(':billamount', $billamount, PDO::PARAM_STR);
        $query->bindParam(':studentClass', $studentClass, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $msg = "Student info updated successfully";
    }
}

// Rest of your HTML and PHP code...
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SMS Admin| Edit Student < </title>
                <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
                <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
                <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
                <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
                <link rel="stylesheet" href="css/prism/prism.css" media="screen">
                <link rel="stylesheet" href="css/select2/select2.min.css">
                <link rel="stylesheet" href="css/main.css" media="screen">
                <script src="js/modernizr/modernizr.min.js"></script>
    </head>

    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php'); ?>
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                    <?php include('includes/leftbar.php'); ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Edit Student Bills</h2>

                                </div>

                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>

                                        <li class="active">Edit Bill</li>
                                    </ul>
                                </div>

                            </div>
                            <!-- /.row -->
                        </div>
                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">
                                        
                                        <div class="panel-body">
                                            <?php if ($msg) { ?>
                                                <div class="alert alert-success left-icon-alert" role="alert">
                                                    <strong>Well done!</strong><?php echo htmlentities($msg); ?>
                                                </div><?php } else if ($error) { ?>
                                                <div class="alert alert-danger left-icon-alert" role="alert">
                                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                            <?php } ?>
                                            <form class="form-horizontal" method="post">
                                                <?php

                                               // $sql = "SELECT tblstudents.StudentName,tblstudents.RollId,tblstudents.RegDate,tblstudents.StudentId,tblstudents.Status,tblstudents.StudentEmail,tblstudents.Gender,tblstudents.DOB,tblclasses.ClassName,tblclasses.Section from tblstudents join tblclasses on tblclasses.id=tblstudents.ClassId where tblstudents.StudentId=:stid";
                                               $sql = "SELECT * From tblbills where id=:id";
                                               $query = $dbh->prepare($sql);
                                                $query->bindParam(':id', $id, PDO::PARAM_STR);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $result) {  ?>


                                                        <div class="form-group">
                                                            <label for="default" class="col-sm-2 control-label">Student Id</label>
                                                            <div class="col-sm-10">
                                                            <!--<input type="number" name="id" class="form-control" id="id" value="<?php echo htmlentities($result->id) ?>" required="required" autocomplete="off" hidden="true">-->
                                                            <input type="number" name="studentNumber" class="form-control" id="studentNumber" value="<?php echo htmlentities($result->studentNumber) ?>" required="required" autocomplete="off" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="default" class="col-sm-2 control-label">Academic Year</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" name="academicYear" class="form-control" id="academicYear" value="<?php echo htmlentities($result->academicYear) ?>" required="required" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="default" class="col-sm-2 control-label">Term</label>
                                                            <div class="col-sm-10">
                                                                <input type="number" name="term" class="form-control" id="term" value="<?php echo htmlentities($result->term) ?>" required="required" autocomplete="off">
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <label for="default" class="col-sm-2 control-label">Bill Amount</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" name="billamount" class="form-control" id="billamount" value="<?php echo htmlentities($result->billamount) ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="text" class="col-sm-2 control-label">Student Class</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" name="studentClass" class="form-control" value="<?php echo htmlentities($result->studentClass) ?>" id="studentClass">
                                                            </div>
                                                        </div>
                                                       

                                                <?php }
                                                } ?>


                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" name="submit" class="btn btn-warning">Update</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <!-- /.col-md-12 -->
                            </div>
                        </div>
                    </div>
                    <!-- /.content-container -->
                </div>
                <!-- /.content-wrapper -->
            </div>
            <!-- /.main-wrapper -->
            <script src="js/jquery/jquery-2.2.4.min.js"></script>
            <script src="js/bootstrap/bootstrap.min.js"></script>
            <script src="js/pace/pace.min.js"></script>
            <script src="js/lobipanel/lobipanel.min.js"></script>
            <script src="js/iscroll/iscroll.js"></script>
            <script src="js/prism/prism.js"></script>
            <script src="js/select2/select2.min.js"></script>
            <script src="js/main.js"></script>
            <script>
                $(function($) {
                    $(".js-states").select2();
                    $(".js-states-limit").select2({
                        maximumSelectionLength: 2
                    });
                    $(".js-states-hide").select2({
                        minimumResultsForSearch: Infinity
                    });
                });
            </script>
    </body>

    </html>
<?PHP  ?>