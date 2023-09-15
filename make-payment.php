<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit; // Terminate script execution to prevent further code execution
} else {
    $id = intval($_GET['id']);

    $msg = ''; // Initialize a message variable

    if (isset($_POST['submit'])) {
        $studentNumber = $_POST['studentNumber'];
        $paymentAmount = $_POST['paymentAmount'];
        $paymentStatus = $_POST['paymentStatus'];
        $studentBalance = $_POST['studentBalance'];
        $contact = 0;

        $sql = "SELECT tblguardianinfo.contact From tblguardianinfo join tblstudents on tblguardianinfo.studentnumber=tblstudents.id where id=:id";
                                            $getquery = $dbh->prepare($sql);
                                            $getquery->bindParam(':id', $id, PDO::PARAM_STR);
                                            $getquery->execute();
                                            $getresults = $getquery->fetch(PDO::FETCH_OBJ);

        $sql = "UPDATE tblaccounts SET studentNumber=:studentNumber, paymentAmount=:paymentAmount, paymentStatus=:paymentStatus, studentBalance=:studentBalance WHERE id=:id";
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentNumber', $studentNumber, PDO::PARAM_STR);
        $query->bindParam(':paymentAmount', $paymentAmount, PDO::PARAM_STR);
        $query->bindParam(':paymentStatus', $paymentStatus, PDO::PARAM_STR);
        $query->bindParam(':studentBalance', $studentBalance, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $msg = "Payment made successfully";
        // You can add more logic here if needed, but no redirection should be performed.


        
    // set up the request headers
    $headers = [
        'Host: api.smsonlinegh.com',
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: key 49244f8ffc3ed558188b699747d07186a675db279f9b42408ead155535b78571'
    ];
	
    // set up the message data
    $messageData = [
        'text'=>'GHS '.$paymentAmount.'as Fees. Payment status: '.$paymentStatus.' Balance :'.$studentBalance,
        'type'=> 0,	// GSM default
        'sender'=> 'TEST',
        'destinations'=> [$getresults->contact]
    ];
	
    // initialise cURL
    $ch = curl_init('https://api.smsonlinegh.com/v5/sms/send');
    
    // set cURL optionS
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute for response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // close curl
    curl_close($ch);

if ($httpCode == 200){
    var_dump($response);
}




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
    <title>SMS Admin| Make Payment </title>
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
                                <h2 class="title">Make Payment</h2>

                            </div>

                            <!-- /.col-md-6 text-right -->
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>

                                    <li class="active">Payment</li>
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
                                            $sql = "SELECT * From tblaccounts where id=:id";
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
                                                        <label for="default" class="col-sm-2 control-label">Amount Owning</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="billAmount" class="form-control" id="billAmount" value="<?php echo htmlentities($result->studentBalance) ?>" required="required" autocomplete="off" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Payment</label>
                                                        <div class="col-sm-10">
                                                            <input type="number" name="paymentAmount" class="form-control" id="paymentAmount" value="<?php echo htmlentities($result->paymentAmount) ?>" required="required" autocomplete="off">
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Payment Status</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="paymentStatus" class="form-control" id="paymentStatus" value="<?php echo htmlentities($result->paymentStatus) ?>" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="text" class="col-sm-2 control-label">Student Balance</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="studentBalance" id="studentBalance" class="form-control" value="<?php echo htmlentities($result->studentBalance) ?>" id="studentClass" readonly>
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

<script>

$(document).ready(function() {
    $('#paymentAmount').on('input', function() {
        var billAmount = parseFloat($('#billAmount').val());
        var paymentAmount = parseFloat($('#paymentAmount').val());

        // Check if any of the values are NaN, and set them to 0 if they are
        if (isNaN(billAmount)) {
            billAmount = 0;
        }
        if (isNaN(paymentAmount)) {
            paymentAmount = 0;
        }

        var studentBalance = billAmount - paymentAmount;
        
        // Update the studentBalance field with the calculated amount
        $('#studentBalance').val(studentBalance);

        // Set the paymentStatus based on the studentBalance
        var paymentStatus = $('#paymentStatus');
        if (studentBalance < 0) {
            paymentStatus.val("Over Payment");
        } else if (studentBalance > 0) {
            paymentStatus.val("Owning");
        } else if (studentBalance === 0) {
            paymentStatus.val("Fully Paid");
        }
    });
});
</script>