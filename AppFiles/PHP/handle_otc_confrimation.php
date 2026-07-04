<?php



//handle POST request with one-time confirmation
header('Content-Type: application/json');
$servername = "127.0.0.1";
$username = "root";
$password = "50425175";
$dbname = "poll_penguin_revamp"; // Your database name

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $stmt = $conn->prepare('SELECT username, options FROM userinfo WHERE otp = ?');
    $stmt->bind_param('s', $received);
    $stmt->execute();
    $stmt->bind_result($user, $role);
    $stmt->fetch();
    $stmt->close();

    if ($user) {
        echo json_encode([
            'ok' => true,
            'message' => 'One-Time Password confirmation success',
            'username' => $user,
            'role' => $role
        ]);
        ;

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


?>
