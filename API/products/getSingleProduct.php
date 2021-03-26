<?php
include('../../config/Database_conn.php');
include('../../objects/Product.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$product = new Product($db);

$product->Id = isset($_GET['Id']) ? $_GET['Id'] : die();
$product->singleProduct();
$product_array = array(
    'Id' => $product->Id,
    'name' => $product->Name,
    'description' => $product->Description,
    'model' => $product->Model,
    'price' => $product->Price

);

print_r(json_encode($product_array));
