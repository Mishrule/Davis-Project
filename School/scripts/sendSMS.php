
<?php
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Function to fetch data based on ID
    function fetchDataBasedOnId($id) {
        // Replace this with your actual database connection code
        $dbh = new PDO('mysql:host=localhost;dbname=srms', 'root', '');
        
        // Replace this with your actual database query
        $sql = "SELECT * FROM tblaccounts JOIN tblstudents ON tblstudents.StudentId=tblaccounts.studentNumber WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        
        // Fetch the data (you may need to adjust this depending on your database structure)
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }

    // Perform a database query or any other processing based on the 'id'
    // Replace this with your actual query or processing logic
    $result = fetchDataBasedOnId($id);

    if ($result) {
        // If data is found and processed successfully
        $headers = [
            'Host: api.smsonlinegh.com',
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: key d88211d0faa9300c31bcb127efa348f15e7b8cce3852ee7c1a302979adeded87'
        ];

        // Initialize the message data
        $messageData = [];

        if ($result['paymentStatus'] == 'Fully Paid') {
            $messageData = [
                'text' => 'Your Ward has fully paid the fees',
                'type' => 0,    // GSM default
                //'sender' => 'TEST',
                'sender' => 'SRMS_PAY',
                'destinations' => [$result['guardianContact']]
            ];
        } else {
            $messageData = [
                'text' => 'Your Ward is owing GHS ' . $result['studentBalance'].' as Fees',
                'type' => 0,    // GSM default
                //'sender' => 'TEST',
                'sender' => 'SRMS_PAY',
                'destinations' => [$result['guardianContact']]
            ];
        }

        // Set up cURL
        $ch = curl_init('https://api.smsonlinegh.com/v5/sms/send');
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute and get the response
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL
        curl_close($ch);
        $responses = [];
        if ($httpCode == 200) {
            // If the SMS was sent successfully
            $responses = [
                'success' => true,
                'message' => 'SMS sent successfully.',
            ];
        } else {
            // If there's an error sending the SMS
            $responses = [
                'success' => false,
                'message' => 'SMS could not be sent. HTTP Code: ' . $httpCode,
            ];
        }
    } else {
        // If there's an error or data not found
        $responses = [
            'success' => false,
            'message' => 'Data not found or an error occurred.',
        ];
    }
} else {
    // If 'id' is not sent via POST
    $responses = [
        'success' => false,
        'message' => 'No ID received.',
    ];
}

// Send the response back as JSON
header('Content-Type: application/json');
echo json_encode($responses);
?>