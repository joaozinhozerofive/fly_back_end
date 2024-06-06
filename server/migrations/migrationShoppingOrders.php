<?php

function migrationShoppingOrdes() {
    $sql = "CREATE TABLE IF NOT EXISTS shopping_orders(
            id SERIAL PRIMARY KEY,
            status INT DEFAULT 1 CHECK(status IN (1,2,3, 4)),
            user_id INT, 
            adress_id INT,
            total_price NUMERIC(10, 2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id)    REFERENCES users(id),
            FOREIGN KEY (adress_id)  REFERENCES adress(id)
            );";

    migration($sql);
 }

 function migrationOrderProducts() {
    $sql = "CREATE TABLE IF NOT EXISTS order_products (
            order_id INT,
            product_id INT
            );";

    migration($sql);
 }