<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //Handle GET request
    $data = [
        'name' => 'Veldora',
        'age' => 1000,
        'type' => 'Dragon'
    ];

    echo json_encode($data);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Handle POST request
    $req = file_get_contents('php://input');
    $data = json_decode($req, true);//should return array


    if (is_array($data)) {
        $data['status'] = 'Data received';
    } else {
        $data = ['Error' => 'Invalid data'];
    }


    echo json_encode($data);

} else {
    //handle invalid requests
    echo json_encode(['Error' => 'Invalid request']);

}


?>