<?php
include('../../config/Database_conn.php');
include('../../objects/User.php');

//call database object
$database = new Database();
$db = $database->connect();

//user object
$user = new User($db);

if (!empty($_GET['username'])) {

    $user->Username = $_GET['username'];
} else {
    $error = new stdClass();
    $error->message = "Username is not set";
    $error->code = "008";
    print_r(json_encode($error));
    die();
}
if (!empty($_GET['email'])) {

    $user->Email = $_GET['email'];
} else {
    $error = new stdClass();
    $error->message = "Email is not set";
    $error->code = "009";
    print_r(json_encode($error));
    die();
}

if (!empty($_GET['password'])) {

    $user->Password = $_GET['password'];
} else {
    $error = new stdClass();
    $error->message = "Password is not set";
    $error->code = "009";
    print_r(json_encode($error));
    die();
}

$user->createUser();
