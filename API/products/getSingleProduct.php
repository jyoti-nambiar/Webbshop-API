<?php
include('../../config/Database_conn.php');
include('../../objects/Product.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$product = new Product($db);



if (isset($_GET['id'])) {
    $product->Id = $_GET['id'];
    $product->singleProduct();
    $product_array = array(
        'Id' => $product->Id,
        'name' => $product->Name,
        'description' => $product->Description,
        'model' => $product->Model,
        'price' => $product->Price

    );
    print_r(json_encode($product_array));
} else {
    $error = new stdClass();
    $error->message = "The product id is not set";
    $error->code = "002";
    print_r(json_encode($error));
}
