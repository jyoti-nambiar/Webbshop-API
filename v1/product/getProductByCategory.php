<?php
include('../../config/Database_conn.php');
include('../../objects/Product.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$product = new Product($db);

if (isset($_GET['category'])) {
    $product->Model = $_GET['category'];
    $result = $product->getByCategory();
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
        echo json_encode(array('message' => 'No products found'));
    }
} else {
    $error = new stdClass();
    $error->message = "The product category is not specified";
    $error->code = "001";
    print_r(json_encode($error));
}
