<?php 

function migrationShooppingCart() {
    $sql = "CREATE TABLE IF NOT EXISTS shopping_cart(
            finished INT CHECK(finished IN (0,1)),
            user_id INT, 
            product_id INT, 
            FOREIGN KEY (product_id) REFERENCES products(id),
            FOREIGN KEY (user_id)    REFERENCES users(id)
            );";

    migration($sql);
 }