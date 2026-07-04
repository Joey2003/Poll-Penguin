<?php
session_start();

// Initialize feedback counts if not already set
if (!isset($_SESSION['feedback_counts'])) {
    $_SESSION['feedback_counts'] = ['Lost' => 0, 'Just Right' => 0, 'Easy' => 0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'reset') {
        // Reset feedback counts
        $_SESSION['feedback_counts'] = ['Lost' => 0, 'Just Right' => 0, 'Easy' => 0];
    } elseif (isset($_POST['category'])) {
        $category = $_POST['category'];

        // Update feedback counts
        if (array_key_exists($category, $_SESSION['feedback_counts'])) {
            $_SESSION['feedback_counts'][$category]++;
        }
    }

    // Return updated feedback counts as JSON
    echo json_encode(array_values($_SESSION['feedback_counts']));
} else {
    // Return current feedback counts as JSON
    echo json_encode(array_values($_SESSION['feedback_counts']));
}
?>