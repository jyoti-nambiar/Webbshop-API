<?php
include('../../config/Database_conn.php');
include('../../objects/Cart.php');
//call database object
$database = new Database();
$db = $database->connect();

//create new cart object
$cart = new Cart($db);

if (!empty($_GET['productid'])) {

    $cart->ProductId = $_GET['productid'];
} else {
    $error = new stdClass();
    $error->message = "Product id is not specified";
    $error->code = "005";
    print_r(json_encode($error));
}
if (!empty($_GET['orderid'])) {
    $cart->OrderId = $_GET['orderid'];
} else {
    $error = new stdClass();
    $error->message = "Order Id is not specified";
    $error->code = "0011";
    print_r(json_encode($error));
    die();
}
$cart->deleteCartItem();
