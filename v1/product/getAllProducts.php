<?php
include('../../config/Database_conn.php');
include('../../objects/Product.php');
include('../../objects/User.php');
//call database object
$database = new Database();
$db = $database->connect();



//call product object
$product = new Product($db);
//get all products in the webshop
$result = $product->getProducts();
$num = $result->rowCount();
$product_array = array();
$product_array['Product'] = array();

if ($num > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $product_item = array(
            'Id' => $Id,
            'name' => $Name,
            'description' => $Description,
            'model' => $Model,
            'price' => $Price
        );

        array_push($product_array['Product'], $product_item);
    }

    echo json_encode($product_array);
} else {
    $error = new stdClass();
    $error->message = "No products found";
    $error->code = "0014";
    print_r(json_encode($error));
}
