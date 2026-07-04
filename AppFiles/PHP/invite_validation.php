<?php

    error_reporting(E_ALL); // Enable error reporting
    ini_set('display_errors', 1); // Set display_errors to 1

    header('Content-Type: application/json'); // Response is JSON
    require_once 'db_connect.php'; // Connect to database

    function validStudent($email) // Definition of validStudent function
    {
        return strpos($email, '@buffalo.edu') !== false; // Check if email contains @buffalo.edu
    }

    function getStudentsIncourse($courseId) // Definition of getStudentsIncourse function
    {
        global $conn; // Establish connection to database
        $query = "SELECT student_first_name, student_last_name, student_email FROM Courses WHERE course_id = ?"; // SQL query
        $stmt = $conn->prepare($query); // Prepare the query
        $stmt->bind_param("s", $courseId); // Bind parameters
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result
        $students = []; // Initialize an empty array
        while($row = $result->fetch_assoc()) // Loop through the result
        {
            $students[] = $row; // Add the row to the array
        }
        return $students; // Return the array
    }

    function addStudentToCourse($courseId, $firstName, $lastName, $email) // Definition of addStudentToCourse function
    {
        global $conn; // Establish connection to database
        $query = "INSERT INTO Courses (course_id, student_first_name, student_last_name, student_email) VALUES (?, ?, ?, ?)"; // SQL query
        $stmt = $conn->prepare($query); // Prepare the query
        $stmt->bind_param("ssss", $courseId, $firstName, $lastName, $email); // Bind parameters
        return $stmt->execute(); // Execute the query
    }

    function courseExists($courseId) // Definition of courseExists function
    {
        global $conn; // Establish connection to database
        $query = "SELECT COUNT(*) FROM Courses WHERE course_id = ?"; // SQL query
        $stmt = $conn->prepare($query); // Prepare the query
        $stmt->bind_param("s", $courseId); // Bind parameters
        $stmt->execute(); // Execute the query
        $stmt->bind_result($count); // Bind the result
        $stmt->fetch(); // Fetch the result
        return $count > 0; // Return true if the count is greater than 0
    }

    $conn = new mysqli($servername, $username, $password, $dbname); // Connect to database

    if($conn->connect_error) // Check if connection failed
    {
        die(json_encode(['ok'=> false, 'message' => 'Database connection failed: ' . $conn->connect_error])); // Return error in JSON
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') // Handle the POST request
    {
        $postInfo = json_decode(file_get_contents('php://input'), true); // Parse the POST data

        if($postInfo === null && json_last_error() !== JSON_ERROR_NONE) // Check if POST data is invalid
        {
            die(json_encode(['ok' => false, 'message' => 'Invalid POST data'])); // Return error in JSON
        }

        /* Combine prefix and number into course ID */
        $prefix = $postInfo['prefix']; // Prefix value (CS)
        $number = $postInfo['number']; // Course number (101)

        $name = $postInfo['name']; // Course name (Introduction to Programming)
        $term = $postInfo['term']; // Term value (Fall 2024)
        $students = $postInfo['students']; // Array of students

        $courseId = $prefix . $number; // Course ID (CS101)

        $results = []; // Initialize an empty results array
        $existingEmails = []; // Initialize emails array

        if(courseExists($courseId)) // Check if course exists
        {
            $existingStudents = getStudentsIncourse($courseId); // Get the students in the course
            $existingEmails = array_column($existingStudents, 'student_email'); // Get the email addresses of the students in the course
        }

        foreach($students as $student) // Loop through the students
        {
            if(validStudent($student['email']) && !in_array($student['email'], $existingEmails)) // Check if the email is valid and not already in the course
            {
                if (addStudentToCourse($courseId, $student['firstName'], $student['lastName'], $student['email'])) // Add the student to the course
                {
                    $results[] = ['firstName' => $student['firstName'], 'lastName' => $student['lastName'], 'email' => $student['email']]; // Add the student to the results array
                    $existingEmails[] = $student['email']; // Add the email to the emails array
                }
            }
        }

        $enrolledStudents = getStudentsIncourse($courseId); // Get the students in the course

        echo json_encode(['ok' => true, 'message' => $enrolledStudents]); // Return the students in the course in JSON

        $conn->close(); // Close the connection
    }