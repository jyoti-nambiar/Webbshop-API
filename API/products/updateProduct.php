<?php
include('../../config/Database_conn.php');
include('../../objects/Product.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$product = new Product($db);
if (!empty($_GET['id'])) {

    $product->Id = $_GET['id'];
} else {
    $error = new stdClass();
    $error->message = "product id is not selected";
    $error->code = "002";
    print_r(json_encode($error));
}


if (!empty($_GET['name'])) {

    $product->Name = $_GET['name'];
} else {
    $error = new stdClass();
    $error->message = "product name is not set";
    $error->code = "004";
    print_r(json_encode($error));
}
if (!empty($_GET['description'])) {

    $product->Description = $_GET['description'];
} else {
    $error = new stdClass();
    $error->message = "product description is not set";
    $error->code = "005";
    print_r(json_encode($error));
}
if (!empty($_GET['model'])) {

    $product->Model = $_GET['model'];
} else {
    $error = new stdClass();
    $error->message = "product model is not set";
    $error->code = "006";
    print_r(json_encode($error));
}

if (!empty($_GET['price'])) {

    $product->Price = $_GET['price'];
} else {
    $error = new stdClass();
    $error->message = "product price is not set";
    $error->code = "007";
    print_r(json_encode($error));
    die();
}

$product->updateProduct();
