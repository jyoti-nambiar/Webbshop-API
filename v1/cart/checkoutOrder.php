<?php
include('../../config/Database_conn.php');
include('../../objects/Cart.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$cart = new Cart($db);

if (!empty($_GET['orderid'])) {

    $cart->OrderId = $_GET['orderid'];
} else {
    $error = new stdClass();
    $error->message = "order id is not specified";
    $error->code = "0011";
    print_r(json_encode($error));
    die();
}

$cart->checkoutOrder();
