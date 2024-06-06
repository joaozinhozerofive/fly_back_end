<?php 

function migrationUsersHelp() {
    $sql = "CREATE TABLE IF NOT EXISTS users_help (
            id SERIAL PRIMARY KEY, 
            user_name VARCHAR (100), 
            email VARCHAR (254), 
            description VARCHAR
            );";

    migration($sql);
 }