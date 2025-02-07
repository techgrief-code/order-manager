CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orderId VARCHAR(255) NOT NULL,
    productName VARCHAR(255) NOT NULL,
    customerName VARCHAR(255) NOT NULL,
    productPrice DECIMAL(10, 2) NOT NULL,
    transportPrice DECIMAL(10, 2) NOT NULL,
    senditPrice DECIMAL(10, 2) NOT NULL,
    orderDate DATE NOT NULL,
    arrivalDate DATE,
    taxCost DECIMAL(5, 2) NOT NULL,
    profit DECIMAL(5, 2) NOT NULL
);
