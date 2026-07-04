<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';
function credentials_exist($username, $password)
{

    if (empty($username) || empty($password)) {
        return false;
    }

    $servername = "127.0.0.1";
    $db_username = "root";
    $db_password = "50425175";
    $dbname = "poll_penguin_revamp"; // Your database name

    // Create connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        return false;
    }
    $sql = $conn->prepare("SELECT password, options FROM `userinfo` WHERE username = ?"); // TODO: SELECT otc too
    if ($sql === false) {
        return false;
    }
    $sql->bind_param("s", $username);
    $sql->execute();
    $sql->store_result();

    // Close connection
    $conn->close();

    if ($sql->num_rows > 0) {
        $sql->bind_result($hashed_pass, $role);
        $sql->fetch();
        if (password_verify($password, $hashed_pass)) {
            // TODO: if otc === null
            return $role;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function sendEmail($email, $otp)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->Port       = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
        $mail->SMTPAuth   = true;
        $mail->Username   = 'joeynorm@buffalo.edu';  // your email
        $mail->Password   = 'xxxxxxxxxxxxxxxx';      // Gmail app password

        // Recipients
        $mail->setFrom('joeynorm@buffalo.edu', 'Poll Penguin');
        $mail->addAddress($email);  // send OTP to this email

        // Content
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = 'Your OTP code is: ' . $otp;

        $mail->send();
        return true;  // success
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false; // failure
    }
}

function new_account($username, $password, $role)
{


    $servername = "127.0.0.1";
    $db_username = "root";
    $db_password = "50425175";
    $dbname = "poll_penguin_revamp"; // Your database name
    $alpha = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
    $otp = "";

    for ($i = 0; $i < 7; $i++) {
        $char = '';
        if (rand(0, 1) == 1) {
            $char = $alpha[rand(0, 25)];
        } else {
            $char = "" . rand(0, 9);
        }
        $otp = $otp . $char;
    }


    // Create connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    // $sql = $conn->prepare("SELECT password FROM `userinfo` WHERE username = ?"); // TODO: SELECT otc too
    // if ($sql === false) {
    //     return false;
    // }
    // $sql->bind_param("s", $username);
    // $sql->execute();
    // $sql->store_result();
    // if ($sql->num_rows > 0) {
    //     $sql->bind_result($hashed_pass); // TODO: bind otp too
    //     $sql->fetch();
    //     if (password_verify($password, $hashed_pass)) {
    //         //return otp
    //     }
    // }
    $sql = $conn->prepare("INSERT INTO `userinfo`(`username`, `password`, `options`, `otp`) VALUES (?,?,?,?)");
    if ($sql === false) {
        return false;
    }
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
    $sql->bind_param("ssss", $username, $hashed_pass, $role, $otp);
    $res = $sql->execute();
    if ($res === false) {
        return false;
    }
    sendEmail($username, $otp);
    // Close connection
    $conn->close();
    return $otp;
}
