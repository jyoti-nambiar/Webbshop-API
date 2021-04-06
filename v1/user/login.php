<?php
include('../../config/Database_conn.php');
include('../../objects/User.php');

//call database object
$database = new Database();
$db = $database->connect();

$username = $_GET['username'];
$password = $_GET['password'];
$salt = 'thisISForPassword$$Protection';

//user object
$user = new User($db);
$user->Username = $username;
$user->Password = md5($password . $salt);

$result = $user->login();

$return = new stdClass();
$return->token = $result;
print_r(json_encode($return));
