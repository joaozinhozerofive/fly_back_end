<?php

function migrationAdress() {
    $sql = "CREATE TABLE IF NOT EXISTS adress (
            id SERIAL PRIMARY KEY, 
            user_id INT, 
            FOREIGN KEY (user_id) REFERENCES users(id), 
            street_adress VARCHAR (100), 
            residence_code INT,
            country VARCHAR (60), 
            city VARCHAR (60),
            district VARCHAR (60),
            state VARCHAR (2),
            CEP VARCHAR(15),
            complement VARCHAR (60)
        );";
    
    migration($sql);
 }