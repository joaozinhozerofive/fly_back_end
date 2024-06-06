<?php 
function migrationProduct() {
    $sql = "CREATE TABLE IF NOT EXISTS products (
            id SERIAL PRIMARY KEY, 
            product_name VARCHAR, 
            product_price NUMERIC(10, 2), 
            product_description VARCHAR,
            old_price NUMERIC(10, 2),
            subcategory_id INT,
            FOREIGN KEY (subcategory_id) REFERENCES subcategories(id)
            );";

    migration($sql);
 }

 function migrationProductImages() {
    $sql = "CREATE TABLE IF NOT EXISTS product_images (
            image VARCHAR, 
            product_id INT, 
            FOREIGN KEY (product_id) REFERENCES products(id)
            );";

    migration($sql);
 }