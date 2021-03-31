<?php
include('../../config/Database_conn.php');
include('../../objects/User.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$user = new User($db);

$result = $user->getUsers();

$num = $result->rowCount();
$user_array = array();
$user_array['User'] = array();

if ($num > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $user_detail = array(
            'Id' => $Id,
            'Username' => $Username,
            'Email' => $Email,
            'Password' => $Password

        );

        array_push($user_array['User'], $user_detail);
    }

    echo json_encode($user_array);
} else {
    echo json_encode(array('message' => 'No user found'));
}
