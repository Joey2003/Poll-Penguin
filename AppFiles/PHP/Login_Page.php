<?php
require_once "credentialsValidation.php";



error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // 获取并验证用户输入
    $postData = json_decode(file_get_contents('php://input'), true);
    $username = isset($postData['username']) ? trim($postData['username']) : '';
    $password = isset($postData['password']) ? trim($postData['password']) : '';

    $_SESSION['username'] = $username;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // 检查用户名是否已经存在
        //call the credentials_exist function from credentialsValidation.php
        $exists = credentials_exist($username, $password);
        if (!$exists) {
            // 用户不存在
            echo json_encode(["ok" => false, "message" => "Invalid username or password"]); // jsonify the result and return it
            return;
        } else {
            // 用户存在
            echo json_encode(["ok" => true, "message" => "Login successful", "role" => $exists]); // jsonify the result and return it // Added role to the response
        }
    }
} catch (Exception $e) {
    // If there's an error, send a JSON response with the error message
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
}


?>
