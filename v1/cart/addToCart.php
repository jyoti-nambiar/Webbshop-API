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
    $cart->OrderId = $row['Token'];

    $cart->UserId = $row['User_Id'];


    if ($user->isTokenValid($cart->OrderId)) {

        $cart->addProductInCart();
    } else {
        $error = new stdClass();
        $error->message = "Session logged out, please login again";
        $error->code = "0010";
        print_r(json_encode($error));
        die();
    }
}
