<?php
include('../../config/Database_conn.php');
include('../../objects/Cart.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$cart = new Cart($db);

if (!empty($_GET['orderId'])) {

    $cart->OrderId = $_GET['orderId'];
} else {
    $error = new stdClass();
    $error->message = "order id is not specified";
    $error->code = "0013";
    print_r(json_encode($error));
}



$cart->checkoutOrder();
