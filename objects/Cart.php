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
}
