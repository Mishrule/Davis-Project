<?php
error_reporting(0);
$output = "";
    if(!empty($_FILES['fileUpload']['name'])){
        $connect = mysqli_connect("localhost","root","","srms");
        
        $allowed_ext = array("csv");
        $fileName = $_FILES["fileUpload"]["name"];
        $extension = end(explode(".", $fileName));
        try {
            if(in_array($extension, $allowed_ext)){
                $file_data = fopen($_FILES["fileUpload"]["tmp_name"], "r");
                fgetcsv($file_data);
                while($row = fgetcsv($file_data)){
                            $student_number = mysqli_real_escape_string($connect, $row[0]);
                            $student_academicYear = mysqli_real_escape_string($connect, $row[1]);
                            $student_term = mysqli_real_escape_string($connect, $row[2]);
                            $student_amount = mysqli_real_escape_string($connect, $row[3]);
                            $student_class = mysqli_real_escape_string($connect, $row[4]);
                            
                        $query = "INSERT INTO tblbills VALUES('','$student_number','$student_academicYear', '$student_term','$student_amount','$student_class')";
                        $insertPayment = "INSERT INTO tblaccounts VALUES('','$student_number','$student_amount', '0.0','$student_term','$student_academicYear','owing','$student_amount')";
                           $result = mysqli_query($connect, $query);
                           $resultPayment = mysqli_query($connect, $insertPayment);
                           if($result && $resultPayment){
                              $output = 'Records saved successfully';
                           
                           }else{
                            $output = mysqli_error($connect) .'Records failed';
                           
                           }
                }
            }else{
              $output = 'Invalid File Extension, the file must be csv';
              
            }
        } catch (Exception $e) {
          $output = $e->getMessage();
           
        }
        
    }else{
      $output = 'File not supported please use the template';

    }
    echo $output;
?>