<!DOCTYPE html>
<html>
<head>
    <title>Credential Test</title>
</head>
<body>
    <?php
    function credentials_exist($username, $password){
        $servername = "oceanus.cse.buffalo.edu";
        $db_user = "joeynorm";
        $db_pass = "50425175";
        $dbname = "cse442_2024_summer_team_d_db";

        // Create connection
        $conn = new mysqli($servername, $db_user, $db_pass, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $sql = $conn->prepare("SELECT * FROM userinfo WHERE username = ? AND password = ?");
        $sql->bind_param("ss", $username, $password);
        $sql->execute();
        $result = $sql->get_result();

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
            $servername = "oceanus.cse.buffalo.edu";
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
                $otp .= $char;
            }

            // Create connection
            $conn = new mysqli($servername, $db_user, $db_pass, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and bind
            $sql = $conn->prepare("INSERT INTO userinfo (username, password, otp) VALUES (?, ?, ?)");
            $sql->bind_param("sss", $username, $password, $otp);

            if ($sql->execute() === FALSE) {
                return FALSE;
            }

            // Close connection
            $conn->close();
            return $otp;
        }
        return false;
    }

    // Insert a test record for the valid credential test
    function insertTestRecord($username, $password, $email, $role) {
        $servername = "oceanus.cse.buffalo.edu";
        $db_user = "joeynorm";
        $db_pass = "50425175";
        $dbname = "cse442_2024_summer_team_d_db";

        // Create connection
        $conn = new mysqli($servername, $db_user, $db_pass, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO userinfo (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $password, $email, $role);

        // Execute the statement
        $stmt->execute();

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }

    // Insert a test record
    insertTestRecord('student1', 'password2', 'student1@example.com', 'student');

    // Run the test and display the result
    $result = credentials_exist('student1', 'password2');
    echo "<p>Test with Valid Credentials: " . ($result ? 'Pass' : 'Fail') . "</p>";
    ?>
</body>
</html>

