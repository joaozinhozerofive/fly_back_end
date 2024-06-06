<?php

function migrationshowcaseBanner() {
    $sql = "CREATE TABLE IF NOT EXISTS showcase_banner (
            id SERIAL PRIMARY KEY, 
            page_name VARCHAR (350),
            image VARCHAR NOT NULL, 
            banner_title VARCHAR (100)
            );";

    migration($sql);
 }