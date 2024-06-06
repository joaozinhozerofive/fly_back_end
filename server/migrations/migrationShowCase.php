<?php 
function migrationShowCase() {
    $sql = "CREATE TABLE IF NOT EXISTS showcase (
            id SERIAL PRIMARY KEY,
            showcase_name VARCHAR (100),
            page_name VARCHAR (350),
            product_id INT,
            FOREIGN KEY (product_id) REFERENCES products(id)
            );";
    
    migration($sql);
 }