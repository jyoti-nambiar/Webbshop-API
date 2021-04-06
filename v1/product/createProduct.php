<?php
include('../../config/Database_conn.php');
include('../../objects/Product.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$product = new Product($db);

if (!empty($_GET['name'])) {

    $product->Name = $_GET['name'];
} else {
    $error = new stdClass();
    $error->message = "product name is not specified";
    $error->code = "004";
    print_r(json_encode($error));
}
if (!empty($_GET['description'])) {

    $product->Description = $_GET['description'];
} else {
    $error = new stdClass();
    $error->message = "product description is not specified";
    $error->code = "007";
    print_r(json_encode($error));
}
if (!empty($_GET['model'])) {

    $product->Model = $_GET['model'];
} else {
    $error = new stdClass();
    $error->message = "product model is not specified";
    $error->code = "008";
    print_r(json_encode($error));
}

if (!empty($_GET['price'])) {

    $product->Price = $_GET['price'];
} else {
    $error = new stdClass();
    $error->message = "product price is not specified";
    $error->code = "009";
    print_r(json_encode($error));
    die();
}

$product->createProduct();
