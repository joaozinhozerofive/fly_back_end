<?php 

function migrationSubCategories() {
    $sql =  "CREATE TABLE IF NOT EXISTS subcategories (
            id SERIAL PRIMARY KEY,  
            subcategory_name  VARCHAR (50),
            parent_category INT,
            FOREIGN KEY (parent_category) REFERENCES categories(id)
            );";

    migration($sql); 
 }