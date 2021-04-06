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
    $error->message = "product id is not specified";
    $error->code = "005";
    print_r(json_encode($error));
    die();
}

$product->deleteProduct();
