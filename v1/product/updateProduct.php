<?php
include('../../config/Database_conn.php');
include('../../objects/Product.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object

$id = "";
$name = "";
$description = "";
$model = "";
$price = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $error = new stdClass();
    $error->message = "product id not specified";
    $error->code = "0005";
    echo json_encode($error);
    die();
}

if (isset($_GET['name'])) {
    $name = $_GET['name'];
}

if (isset($_GET['description'])) {
    $description = $_GET['description'];
}

if (isset($_GET['model'])) {
    $model = $_GET['model'];
}

if (isset($_GET['price'])) {
    $price = $_GET['price'];
}

$product = new Product($db);

//update a product either one or all its parameters
echo json_encode($product->UpdateProduct($id, $name, $description, $model, $price));





























/*
$product = new Product($db);
if (!empty($_GET['id'])) {

    $product->Id = $_GET['id'];
} else {
    $error = new stdClass();
    $error->message = "product id is not specified";
    $error->code = "002";
    print_r(json_encode($error));
}


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

$product->updateProduct();
*/