<?php

function migrationThemeColor() {
    $sql = "CREATE TABLE IF NOT EXISTS public.theme_color_website
(
    color character varying(100) COLLATE pg_catalog.default DEFAULT '#020024'::character varying
);";

    migration($sql);
}