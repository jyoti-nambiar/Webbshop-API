<?php
class Cart
{

    //DB param
    private $conn;
    private $table = 'pendingOrders';

    //cart properties
    public $Id;
    public $OrderId;
    public $ProductId;
    public $Quantity;
    public $UserId;

    //constructor with DB
    function __construct($db)
    {

        $this->conn = $db;
    }

    //Add product to cart
    public function ProductInCart()
    {
        $query = "SELECT User_Id, Token FROM sessions WHERE Id=(SELECT MAX(id) FROM sessions)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt->execute()) {
            echo "Please login first";
        }

        if ($stmt->execute()) {
            $row = $stmt->fetch();
            //print_r($row);
            //getting orderId and UserId from sessions table
            $this->OrderId = $row['Token'];
            //echo $this->OrderId;
            $this->UserId = $row['User_Id'];

            $query = "SELECT * FROM $this->table WHERE ProductId=:productid_IN AND  UserId=:userid_IN AND OrderId=:orderid_IN";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':productid_IN', $this->ProductId);
            $stmt->bindParam(':orderid_IN', $this->OrderId);
            $stmt->bindParam(':userid_IN', $this->UserId);
            $stmt->execute();
            $count = $stmt->rowCount();
            //echo "count" . $count;
            if ($count > 0) {
                $row = $stmt->fetch();
                $this->Id = $row['Id'];
                $query = "Update $this->table SET Quantity= Quantity + 1 WHERE Id= :id_IN";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id_IN', $this->Id);
                $stmt->execute();
            } else {


                $query = "Insert INTO $this->table SET OrderId=:orderid_IN,  ProductId=:productid_IN, Quantity=:quantity_IN, UserId=:userid_IN";
                $stmt = $this->conn->prepare($query);

                // print_r($row);
                //bind functions
                $stmt->bindParam(':orderid_IN', $this->OrderId);
                $stmt->bindParam(':productid_IN', $this->ProductId);
                $stmt->bindParam(':quantity_IN',  $this->Quantity);
                $stmt->bindParam(':userid_IN', $this->UserId);
                if (!$stmt->execute()) {
                    echo "Order not created";
                }
                echo "Order No: $this->OrderId ProductId:$this->ProductId Quantity $this->Quantity User Id $this->UserId";
            }
        }
    }

    function deleteCartItem()
    {
        $query = "DELETE FROM $this->table WHERE ProductId=:productid_IN AND OrderId=:orderid_IN";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':productid_IN', $this->ProductId);
        $stmt->bindParam(':orderid_IN', $this->OrderId);
        if (!$stmt->execute()) {
            $error = new stdClass();
            $error->message = "No product with the id provided exist";
            $error->code = "0003";
            print_r(json_encode($error));
            die();
        }

        echo "Product deleted from cart";
    }

    function checkoutOrder()
    {
        if (!isset($this->OrderId)) {
            $query = "SELECT po.OrderId, u.Username, SUM(po.Quantity) AS Quantity, SUM(p.Price) AS TotalPrice FROM pendingorders AS po JOIN products AS p ON po.ProductId = p.Id JOIN users AS u ON po.UserId = u.Id WHERE po.OrderId='$this->OrderId'";
            $stmt = $this->conn->prepare($query);
            //$stmt->bindParam(':orderid_IN', $this->OrderId);
            if (!$stmt->execute()) {
                $error = new stdClass();
                $error->message = "Orderid does not exist";
                $error->code = "0011";
                print_r(json_encode($error));
                die();
            } else {

                $row = $stmt->fetch();
                print_r($row);
                $user = $row['Username'];
                $count = $row['Quantity'];
                $total = $row['TotalPrice'];
                $this->OrderId = $row['OrderId'];

                $query = "INSERT INTO checkoutorders SET OrderId=:orderid_IN, Username=:username_IN, NumberOfProducts=:numofproducts_IN, TotalAmount=:total_IN";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':orderid_IN', $this->OrderId);
                $stmt->bindParam(':username_IN', $user);
                $stmt->bindParam(':numofproducts_IN', $count);
                $stmt->bindParam(':total_IN', $total);
                if (!$stmt->execute()) {
                    $error = new stdClass();
                    $error->message = "Order could not be checked-out";
                    $error->code = "0012";
                    print_r(json_encode($error));
                    die();
                } else {
                    $query = "DELETE FROM $this->table WHERE OrderId=:orderid_IN";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':orderid_IN', $this->OrderId);
                    $stmt->execute();
                }
            }

            echo "Order No.$this->OrderId. is successfully checked out";
        } else {
            $error = new stdClass();
            $error->message = "Provide a valid orderId to checkout";
            $error->code = "0013";
            print_r(json_encode($error));
            die();
        }
    }
}
