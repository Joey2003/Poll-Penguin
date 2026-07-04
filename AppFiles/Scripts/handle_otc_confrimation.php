<?php



//handle POST request with one-time confirmation
header('Content-Type: application/json');
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    //Connect to database to access otp
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die(json_encode([
            'ok' => false,
            'message' => 'Database connection failed: ' . $conn->connect_error
        ]));
    }
    $received = $_POST['otp'];
    //Prepare and perform query securely
    $stmt = $conn->prepare('SELECT username FROM userinfo WHERE otp = ?');
    $stmt->bind_param('s', $received);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
    //$sql = "SELECT username FROM databse WHERE username = :username;";
    //$expected = $conn->query($sql);
    //$username = $_POST['username'];
    //$expected = '';

    if ($username) {
        echo json_encode([
            'ok' => true,
            'message' => 'One-Time Password confirmation success'
        ]);

        //Invalidate otp after use to ensure one-time usage
        $stmt = $conn->prepare("UPDATE userinfo SET otp = NULL WHERE otp = ?");
        $stmt->bind_param("s", $received);
        $stmt->execute();
        $stmt->close();

    } else {
        echo json_encode([
            'ok' => false,
            'message' => 'Empty/Invalid One-Time Password'
        ]);
    }
    //Close the connection
    $conn->close();
} else {
    echo json_encode([
        'ok' => false,
        'message' => 'Invalid Request/One-Time Password is Required'
    ]);
}



