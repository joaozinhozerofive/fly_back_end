<?php

function migrationCategories() {
    $sql =  "CREATE TABLE IF NOT EXISTS categories (
             id SERIAL PRIMARY KEY,  
             category_name  VARCHAR (50)
            );";

    migration($sql);        
 }