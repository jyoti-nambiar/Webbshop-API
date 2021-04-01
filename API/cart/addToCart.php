<?php
include('../../config/Database_conn.php');
include('../../objects/Cart.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$cart = new Cart($db);


if (!empty($_GET['productId'])) {

    $cart->ProductId = $_GET['productId'];
} else {
    $error = new stdClass();
    $error->message = "product id is not specified";
    $error->code = "005";
    print_r(json_encode($error));
}
if (!empty($_GET['quantity'])) {
    $cart->Quantity = $_GET['quantity'];
} else {
    $error = new stdClass();
    $error->message = "product quantity is not specified";
    $error->code = "005";
    print_r(json_encode($error));
}
$cart->ProductInCart();
