<?php

function migrationThemeColor() {
    $sql = "CREATE TABLE IF NOT EXISTS theme_color_website (
            id SERIAL PRIMARY KEY, 
            color_code VARCHAR (50)
            );";

    migration($sql);
}