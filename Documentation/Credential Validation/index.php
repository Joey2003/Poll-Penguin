<?php
    function credentials_exist($username, $password){
        $servername = "oceanus.cse.buffalo.edu:3306";
	    $db_user = "joeynorm";
	    $db_pass = "50425175";
	    $dbname = "cse442_2024_summer_team_d_db";

	    // Create connection
	    $conn = new mysqli($servername, $db_user, $db_pass, $dbname);
        $sql = $conn->prepare("SELECT * FROM `userinfo` WHERE username = ? AND password = ?");
        $sql->bind_param("ss", $username, $password);
        $result = $conn->execute_query($sql);

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
            $servername = "oceanus.cse.buffalo.edu:3306";
            $db_user = "joeynorm";
	        $db_pass = "50425175";
	        $dbname = "cse442_2024_summer_team_d_db";
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
            $conn = new mysqli($servername, $db_user, $db_pass, $dbname);
            $sql = $conn->prepare("INSERT INTO `userinfo`(`username`, `password`, `otp`) VALUES (?,?,?)");
            $sql->bind_param("sss", $username, $password, $otp);
            if($conn->execute_query($sql) === FALSE) {

                return FALSE;
            }
            
            // Close connection
	        $conn->close();
            return $otp;
        }
    }

?>
