<?php 

function migrationBanner() {
    $sql = "CREATE TABLE IF NOT EXISTS banner (
            id SERIAL PRIMARY KEY, 
            page_name VARCHAR (350),
            image VARCHAR NOT NULL, 
            banner_title VARCHAR (100)
            );";

    migration($sql);
 }