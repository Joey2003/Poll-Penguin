<?php
    function credentials_exist($username, $password){
        $servername = "127.0.0.1";
        $db_username = "root";
        $db_password = "50425175";
        $dbname = "poll_penguin_revamp"; // Your database name

	    // Create connection
	    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
        $sql = "SELECT * FROM `userinfo` WHERE username = $username AND password = $password";
        $result = $conn->query($sql);

        // Close connection
	    $conn->close();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function new_account($username, $password) {

        $exists = credentials_exist($username, $password);

        if (!$exists) {
            $servername = "127.0.0.1";
            $db_username = "root";
            $db_password = "50425175";
            $dbname = "poll_penguin_revamp"; // Your database name
            $alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
            $otp = "";

            for ($i = 0; $i < 7; $i++) {
                $char = '';
                if (rand(0,1) == 1) {
                    $char = $alpha[rand(0,25)];
                } else {
                    $char = "".rand(0,9);
                }
                $otp = $otp . $char;
            }

            // Create connection
            $conn = new mysqli($servername, $db_username, $db_password, $dbname);
            $sql = "INSERT INTO `userinfo`(`username`, `password`, `otp`) VALUES ($username,$password,$otp)";
            if($conn->query($sql) === FALSE) {

                return FALSE;
            }
            
            // Close connection
	        $conn->close();
            return $otp;
        }
    }
?>