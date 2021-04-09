CREATE DATABASE IF NOT EXISTS webshopapi;
SET @salt = 'thisISForPassword$$Protection';
DROP TABLE IF EXISTS checkoutorders;
DROP TABLE IF EXISTS pendingorders;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS products;
CREATE TABLE products(
Id INT NOT NULL AUTO_INCREMENT,
Name VARCHAR(100) NOT NULL, 
Description VARCHAR(100) NOT NULL,
Model VARCHAR(50) NOT NULL,
Price FLOAT NOT NULL,
PRIMARY KEY(Id)
);

INSERT INTO products(Name, Description, Model, Price)
VALUES
('iphone12', '128GB, black, dual sim', 'Apple', 12500),
('Samsung Galaxy 10', '64GB, gold, dual sim','Samsung', 8500),
('Huawei P30Pro', '32GB Gold', 'Huawei', 3300),
('Xiomi Mi 11', '5G 256GB, Black', 'Xiomi',5990 ), 
('Sony Xperia 5', '128GB, Blue', 'Sony', 8990), 
('iphone 6', '64GB,Black', 'Apple', 3500);

CREATE TABLE users(
Id INT NOT NULL AUTO_INCREMENT,
Username VARCHAR(50) NOT NULL, 
Email VARCHAR(100) NOT NULL,
Password VARCHAR(50) NOT NULL,
PRIMARY KEY(Id)
);

INSERT INTO users(Username, Email, Password )
VALUES
('admin', 'admin@gamil.com', MD5(CONCAT('admin',@salt))),
('user', 'user@gmail.com', MD5(CONCAT('user',@salt)));

CREATE TABLE sessions(
Id INT NOT NULL AUTO_INCREMENT,
User_Id INT NOT NULL, 
Token TEXT NOT NULL,
Last_used TEXT NOT NULL,
PRIMARY KEY(Id),
CONSTRAINT FK_User_Id FOREIGN KEY(User_Id) REFERENCES users(Id)
);

CREATE TABLE pendingorders(
Id INT NOT NULL AUTO_INCREMENT,
OrderId TEXT NOT NULL, 
ProductId INT NOT NULL, 
Quantity INT NOT NULL,
UserId INT NOT NULL,
PRIMARY KEY(Id),
CONSTRAINT FK_UserId FOREIGN KEY(UserId) REFERENCES users(Id),
CONSTRAINT FK_ProductId FOREIGN KEY(ProductId) REFERENCES products(Id)
);

CREATE TABLE checkoutorders(
Id INT NOT NULL AUTO_INCREMENT,
OrderId TEXT NOT NULL, 
Username VARCHAR(50) NOT NULL, 
NumberOfProducts INT NOT NULL,
TotalAmount FLOAT NOT NULL,
PRIMARY KEY(Id)
);
ENGINE = InnoDB;