# Mobile Shoppy
Mobile Shoppy is an API project for e-commerce website.The project uses various endpoints to execute all the fuuctionalities which one can find in a e-commerce website.

## Feautures of Project
1. Register a new user by providing credentials like username, email & password.
2. Log-in user by username & password.
3. Adding a new Product to the shop, with details like Name, description, Model (category) & price included.
4. Update & Delete a product.
5. Adding items to cart with product-Id and quantity.
6. View all items in a cart against a order-Id.
7. Delete items from cart by providing the Product-Id and Order-Id.
8. Checkout an order with an Order-Id.
9. Error coding and display fail messages where required.

## Technologies
PHP 5


## Installation
The project runs with the help of XAMPP server , use [XAMPP](https://www.apachefriends.org/download.html) to install XAMPP.

Turn ON the Apache and mysql from the XAMPP control panel.

Fork the project from the Github , use [GitHub](https://github.com/jyoti-nambiar/Webbshop-API.git) to fork the repository and get started.


## Structure(Endpoints)
# User endpoints
1. https://localhost/WebbshopAPI/v1/user/registerUser.php?username=&email=password=
   User registration(provide values after the '=' symbol, _For eg._ _username=user&email=user@gmail.com&password=user_).

2. https://localhost/WebbshopAPI/v1/user/login.php?username=&password=
   User login(_provide proper values after the '=' symbol_).

3. https://localhost/WebbshopAPI/v1/user/getAllUsers.php
    Shows all the resitered users.

# Product endpoints
4. https://localhost/WebbshopAPI/v1/product/createProduct.php?name=&description&model=&price=
Create a new Product(_provide values after the '=' symbol_).

5. https://localhost/WebbshopAPI/v1/product/updateProduct.php?id=&name=&description=&model=&price=
Update an existing product(_provide values after the '=' symbol_).

6. https://localhost/WebbshopAPI/v1/product/deleteProduct.php?id=
Delete a product(_provide values after the '=' symbol_).

7. https://localhost/WebbshopAPI/v1/product/getAllProducts.php
    Get all product available to shop.

8. https://localhost/WebbshopAPI/v1/product/getProductByCategory.php?category=
Get products belong to specific category(brand) _for eg_. category=samsung

9. https://localhost/WebbshopAPI/v1/product/getSingleProduct.php?id=
Get a specific product by entering product id _for eg._ id=3

# Cart endpoints
10. https://localhost/WebbshopAPI/v1/cart/addToCart.php?productid=&quantity=
Add items to cart,by providing product-Id and quantity(_provide values after the '=' symbol_).

11. https://localhost/WebbshopAPI/v1/cart/deleteItem.php?productid=&orderid=
Delete item from cart, by providing product-id and order-id (_provide values after the '=' symbol_).

12. https://localhost/WebbshopAPI/v1/cart/getOrderItems.php?orderid=
Get items in an order , by providing the order-id (_provide values after the '=' symbol_).

13. https://localhost/WebbshopAPI/v1/cart/checkoutOrder.php?orderid=
Checkout an order , by providing an order-id from _pendingorders_ table in the database (_provide values after the '=' symbol_).


## Instructions
1. Register as a user to add items to cart.
2. Login , if you are a registered user, with your username & password.
3. An admin you can Create/Update/delete a product (in version 1 of this project anybody can Create/update/delete a product).
4. As a user you can add items to cart, only when logged in.
5. If the user has been inactive for a period of 1 hour after logging in,The session is timed out , hence he/she needs to login again to add items to cart.
6. The cart items are saved in the pendingorders table, hence can be checked-out at users convinience.
7. Checked out items can be seen with total purchase quantity and Total bill amount.

## Contributions
The work in this project is contributed by Jyoti Nambiar[https://github.com/jyoti-nambiar/Webbshop-API]





