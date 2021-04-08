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
    public function addProductInCart()
    {
        $query = "SELECT User_Id, Token FROM sessions WHERE Id=(SELECT MAX(id) FROM sessions)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt->execute()) {
            echo "Please login first";
        }

        if ($stmt->execute()) {
            $row = $stmt->fetch();

            //getting orderId and UserId from sessions table
            if (!empty($row['Token'])) {
                $this->OrderId = $row['Token'];
            } else {
                echo "Please login to create a new Order";
            }

            $this->UserId = $row['User_Id'];

            //query to see if product exist , if yes only update its quqntity
            $query = "SELECT * FROM $this->table WHERE ProductId=:productid_IN AND  UserId=:userid_IN AND OrderId=:orderid_IN";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':productid_IN', $this->ProductId);
            $stmt->bindParam(':orderid_IN', $this->OrderId);
            $stmt->bindParam(':userid_IN', $this->UserId);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count > 0) { //if product already in cart, update quantity
                $row = $stmt->fetch();
                $this->Id = $row['Id'];
                $this->ProductId = $row['ProductId'];
                $query = "Update $this->table SET Quantity= GREATEST(Quantity + ($this->Quantity), 0) WHERE Id= :id_IN";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id_IN', $this->Id);
                $stmt->execute();
                echo "Product Id.$this->ProductId already in cart, quantity updated";
            } else {
                $query = "Insert INTO $this->table SET OrderId=:orderid_IN,  ProductId=:productid_IN, Quantity=:quantity_IN, UserId=:userid_IN";
                $stmt = $this->conn->prepare($query);

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

    //get all items in an order
    function getOrderItems()
    {
        $query = "SELECT po.OrderId, p.Name, po.Quantity, p.Price FROM $this->table AS po JOIN products AS p ON po.productId= p.Id WHERE po.orderId=:orderid_IN";

        $stmt = $this->conn->prepare($query);
        //bind functions
        $stmt->bindParam(':orderid_IN', $this->OrderId);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count == 0) {
            $error = new stdClass();
            $error->message = "No such order exist";
            $error->code = "0010";
            print_r(json_encode($error));
            die();
        } else {
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //print_r($data);
                echo "<table>";
                echo "<tr><td>Order ID:</td><td>$data[OrderId]</td></tr>";
                echo "<tr><td>Product:</td><td>$data[Name]</td></tr>";
                echo "<tr><td>Quantity</td><td>$data[Quantity]</td></tr>";
                echo "<tr><td>Price</td><td>$data[Price]</td></tr>";
                echo "</table>";
            }
        }
    }


    //delete item from cart

    function deleteCartItem()
    {
        $query = "DELETE FROM $this->table WHERE ProductId=:productid_IN AND OrderId=:orderid_IN";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':productid_IN', $this->ProductId);
        $stmt->bindParam(':orderid_IN', $this->OrderId);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) { //if row deleted
            echo "product deleted from cart";
        } else { //if the order ID or product ID does not exist
            $error = new stdClass();
            $error->message = "No product or order Id exist";
            $error->code = "0003";
            print_r(json_encode($error));
            die();
        }
    }

    function checkoutOrder()
    {
        $query = "SELECT po.OrderId, u.Username, SUM(po.Quantity) AS Quantity, SUM(p.Price) AS TotalPrice FROM pendingOrders AS po JOIN products AS p ON po.ProductId = p.Id JOIN users AS u ON po.UserId = u.Id WHERE po.OrderId=:orderid_IN";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':orderid_IN', $this->OrderId);
        if (!$stmt->execute()) {
            $error = new stdClass();
            $error->message = "Order id does not exist";
            $error->code = "0011";
            print_r(json_encode($error));
            die();
        } else {

            $row = $stmt->fetch();
            $user = $row['Username'];
            $count = $row['Quantity'];
            $total = $row['TotalPrice'];
            if (!empty($row['OrderId'])) {
                //echo $row['OrderId'];
                $this->OrderId = $row['OrderId'];
            } else {
                $error = new stdClass();
                $error->message = "OrderId does not exist, so cannot be checked-out";
                $error->code = "0012";
                print_r(json_encode($error));
                die();
            }
            $query = "INSERT INTO checkoutorders SET OrderId=:orderid_IN, Username=:username_IN, NumberOfProducts=:numofproducts_IN, TotalAmount=:total_IN";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':orderid_IN', $this->OrderId);
            $stmt->bindParam(':username_IN', $user);
            $stmt->bindParam(':numofproducts_IN', $count);
            $stmt->bindParam(':total_IN', $total);
            if (!$stmt->execute()) {
                $error = new stdClass();
                $error->message = "OrderId does not exist, so cannot be checked-out";
                $error->code = "0012";
                print_r(json_encode($error));
                die();
            } else {
                $query = "DELETE FROM $this->table WHERE OrderId=:orderid_IN";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':orderid_IN', $this->OrderId);
                $stmt->execute();
                $sql = "DELETE FROM sessions WHERE Token =:token_IN";
                $stm = $this->conn->prepare($sql);
                $stm->bindParam(':token_IN', $this->OrderId);
                $stm->execute();
            }
        }

        echo "Order No.$this->OrderId. is successfully checked out";
        echo "</br>";
        echo "<h3>Order Details</h3>";
        $query = "SELECT * FROM checkoutorders WHERE OrderId=:orderid_IN";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':orderid_IN', $this->OrderId);
        if ($stmt->execute()) {
            $row = $stmt->fetch();
            echo "<table>";
            echo "<tr><td>Order ID:</td><td>$row[OrderId]</td></tr>";
            echo "<tr><td>Purchased By:</td><td>$row[Username]</td></tr>";
            echo "<tr><td>Total Quantity:</td><td>$row[NumberOfProducts]</td></tr>";
            echo "<tr><td>Total Bill Amount:</td><td>$row[TotalAmount]</td></tr>";
            echo "</table>";
        }
    }
}
