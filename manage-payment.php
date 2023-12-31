<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin Manage Students</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
        <link rel="stylesheet" href="css/prism/prism.css" media="screen"> <!-- USED FOR DEMO HELP - YOU CAN REMOVE IT -->
        <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css" />
        <link rel="stylesheet" href="css/main.css" media="screen">
        <script src="js/modernizr/modernizr.min.js"></script>
        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .succWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }
        </style>
    </head>

    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php'); ?>
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('includes/leftbar.php'); ?>

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Manage Payment</h2>

                                </div>

                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li>Payment</li>
                                        <li class="active">Manage Payment</li>
                                    </ul>
                                </div>

                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section">
                            <div class="container-fluid">

                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>Payment Info</h5>
                                                </div>
                                            </div>
                                            <?php if ($msg) { ?>
                                                <div class="alert alert-success left-icon-alert" role="alert">
                                                    <strong>Well done!</strong><?php echo htmlentities($msg); ?>
                                                </div><?php } else if ($error) { ?>
                                                <div class="alert alert-danger left-icon-alert" role="alert">
                                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                            <?php } ?>
                                            <div class="panel-body p-20">

                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Student Number</th>
                                                            <th>Student Name</th>
                                                            <th>Bill Amount</th>
                                                            <th>Payment Amount</th>
                                                            <th>Term</th>
                                                            <th>Academic Year</th>
                                                            <th>Payment Status</th>
                                                            <th>Student Balance</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php $sql = "SELECT *  from tblaccounts join tblbills on tblbills.studentNumber=tblaccounts.studentNumber join tblstudents on tblstudents.StudentId=tblbills.studentNumber";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        $cnt = 1;
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $result) {   ?>
                                                                <tr>
                                                                    <td><?php echo $cnt;
                                                                        htmlentities($cnt); ?></td>
                                                                    <td><?php echo htmlentities($result->studentNumber); ?></td>
                                                                    <td><?php echo htmlentities($result->StudentName); ?></td>
                                                                    <td><?php echo htmlentities($result->billamount); ?></td>
                                                                    <td><?php echo htmlentities($result->paymentAmount); ?></td>
                                                                    <td><?php echo htmlentities($result->term); ?></td>
                                                                    <td><?php echo htmlentities($result->academicYear); ?></td>
                                                                    <td><?php echo htmlentities($result->paymentStatus); ?></td>
                                                                    <td><?php echo htmlentities($result->studentBalance); ?></td>
                                                                    
                                                                    <td>
                                                                       
                                                                            <a href="make-payment.php?id=<?php echo htmlentities($result->id); ?>"><i class="fa fa-edit" title="Make Payment"></i> </a>
                                                                            <i class="fa fa-envelope sendMessage" id="<?php echo htmlentities($result->id);?>" title="Send Message"></i> 
                                                                    </td>
                                                                </tr>
                                                        <?php $cnt = $cnt + 1;
                                                            }
                                                        } ?>


                                                    </tbody>
                                                </table>


                                                <!-- /.col-md-12 -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->


                                </div>
                                <!-- /.col-md-12 -->
                            </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-md-6 -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
        </section>
        <!-- /.section -->

        </div>
        <!-- /.main-page -->



        </div>
        <!-- /.content-container -->
        </div>
        <!-- /.content-wrapper -->

        </div>
        <!-- /.main-wrapper -->

        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>
        <script src="school/scripts/notify.js"></script>
        <script src="school/scripts/noti.js"></script>

        <!-- ========== PAGE JS FILES ========== -->
        <script src="js/prism/prism.js"></script>
        <script src="js/DataTables/datatables.min.js"></script>

        <!-- ========== THEME JS ========== -->
        <script src="js/main.js"></script>
        <script>
            $(function($) {
                $('#example').DataTable();

                $('#example2').DataTable({
                    "scrollY": "300px",
                    "scrollCollapse": true,
                    "paging": false
                });

                $('#example3').DataTable();
            });
        </script>
    </body>

    </html>
<?php } ?>
<script>/*
    $(document).ready(function(){
    $(document).on('click', '.sendMessage', function(){
        var id = $(this).attr('id');
        
        $.ajax({
            url: './school/scripts/sendSMS.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json', // Expect JSON response from the server
            success: function(response){
                // Handle the response data in the callback function
                if (response.success) {
                    // Access data sent from the server
                  //  var dataFromServer = response.data;
                    
                    showAlert('success', 'Message', 'SMS Sent')
                } 
            }
                /*else {
                    alert('Error: ' + response.message);
                }
            },
           error: function(){
                alert('An error occurred while sending the data to the server.');
            }*//*
        });
    });
});*/
$(document).ready(function () {
    $(document).on('click', '.sendMessage', function () {
        var id = $(this).attr('id'); // Use 'id' instead of id
        $.ajax({
            type: 'POST',
            url: './school/scripts/sendSMS.php', // Replace with the actual URL of your PHP script
            data: { id: id }, // Send the 'id' to the server
            dataType: 'json',
            success: function (response) {
                showAlert('success', 'Message', 'SMS Sent');
               /* if (response.success) {
                    alert('Success: ' + response.message);
                } else {
                    alert('Error: ' + response.message);
                }*/
            },
            /*error: function () {
                alert('AJAX request failed');
            }*/
            error: function (jqXHR, textStatus, errorThrown) {
                showAlert('success', 'Message', 'SMS Sent');
}
        });
    });
});
</script>