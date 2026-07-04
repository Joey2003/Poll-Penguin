<?php
require_once "credentialsValidation.php";

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Set the content type to JSON
header('Content-Type: application/json'); // -Wesley

// 获取并验证用户输入
$postData = json_decode(file_get_contents('php://input'), true);
$username = isset($postData['username']) ? trim($postData['username']) : '';
$password = isset($postData['password']) ? trim($postData['password']) : '';
$role = isset($postData['role']) ? trim($postData['role']) : '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // call create account function from credentialsValidation.php
    

    $exists = credentials_exist($username, $password);

    if ($exists) {
        // 用户不存在
        echo json_encode(["ok" => false, "success" => false, "message" => "An account for $username already exists"]);
        return;
    } else {
        $result = new_account($username, $password, $role);
        if ($result === false) {
            // 用户创建失败
            echo json_encode(["ok" => false, "success" => false, "message" => "Account creation failed"]); 
        } else {

            // 用户创建成功
            echo json_encode(["ok" => true, "success" => true, "message" => "Otp sent to email"]);
            // TODO: Send email with otp ($result)
        }
    }
}

// <?php
// require_once "credentialsValidation.php";

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// // Set the content type to JSON
// header('Content-Type: application/json'); // -Wesley

// // 获取并验证用户输入
// $postData = json_decode(file_get_contents('php://input'), true);
// $username = isset($postData['username']) ? trim($postData['username']) : '';
// $password = isset($postData['password']) ? trim($postData['password']) : '';
// $role = isset($postData['role']) ? trim($postData['role']) : '';


// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // call create account function from credentialsValidation.php
    

//     $exists = credentials_exist($username, $password);

//     if ($exists) {
//         // 用户不存在
//         echo json_encode(["ok" => false, "success" => false, "message" => "An account for $username already exists"]);
//         return;
//     } else {
//         $result = new_account($username, $password, $role);
//         if ($result === false) {
//             // 用户创建失败
//             echo json_encode(["ok" => false, "success" => false, "message" => "Account creation failed"]); 
//         } else {

//             // 用户创建成功
//             echo json_encode(["ok" => true, "success" => true, "message" => "Otp sent to email"]);
//             // TODO: Send email with otp ($result)
//         }
//     }
// }

// dev
?>