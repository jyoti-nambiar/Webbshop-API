<?php
include('../../config/Database_conn.php');
include('../../objects/Cart.php');
include('../../objects/User.php');
//call database object
$database = new Database();
$db = $database->connect();

//call product object
$cart = new Cart($db);


if (!empty($_GET['productid'])) {

    $cart->ProductId = $_GET['productid'];
} else {
    $error = new stdClass();
    $error->message = "product Id is not specified";
    $error->code = "005";
    print_r(json_encode($error));
}
if (!empty($_GET['quantity'])) {
    $cart->Quantity = $_GET['quantity'];
} else {
    $error = new stdClass();
    $error->message = "product quantity is not specified";
    $error->code = "006";
    print_r(json_encode($error));
    die();
}
$user = new User($db);

$query = "SELECT User_Id, Token FROM sessions WHERE Id=(SELECT MAX(id) FROM sessions)";

$stmt = $db->prepare($query);
if (!$stmt->execute()) {
    echo "Please login first";
}

if ($stmt->execute()) {
    $row = $stmt->fetch();


    //getting orderId and UserId from sessions table
    if (!empty($row)) {
        $cart->OrderId = $row['Token'];

        $cart->UserId = $row['User_Id'];
    } else {
        $error = new stdClass();
        $error->message = "Please login to add items to cart";
        $error->code = "0013";
        print_r(json_encode($error));
        die();
    }


    //check token's validity
    if ($user->isTokenValid($cart->OrderId)) {

        $cart->addProductInCart();
    } else { //session logged out after 60 mins , so login to create new token
        $error = new stdClass();
        $error->message = "Session logged out, please login again";
        $error->code = "0010";
        print_r(json_encode($error));
        die();
    }
}
