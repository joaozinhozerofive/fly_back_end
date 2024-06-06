<?php 

function migrationsUsers() {
    $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY, 
            username VARCHAR (100) NOT NULL, 
            email VARCHAR (254) NOT NULL UNIQUE, 
            privilege INT NOT NULL, 
            gender INT CHECK(gender IN (0,1,2)),
            phone VARCHAR (20), 
            birth VARCHAR (10), 
            CPF VARCHAR (15),
            password VARCHAR (500)
            );";
    
    migration($sql);
 }