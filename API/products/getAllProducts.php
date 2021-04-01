<?php
include('../../config/Database_conn.php');
include('../../objects/Product.php');
include('../../objects/User.php');
//call database object
$database = new Database();
$db = $database->connect();

$token = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {

    $error = new stdClass();
    $error->message = "token is not specified";
    $error->code = "0009";
    print_r(json_encode($error));
    die();
}
$user = new User($db);

if ($user->isTokenValid($token)) {


    //call product object
    $product = new Product($db);



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
        echo json_encode(array('message' => 'No products found'));
    }
} else {
    $error = new stdClass();
    $error->message = "Invalid token, please login again";
    $error->code = "0010";
    print_r(json_encode($error));
    die();
}
