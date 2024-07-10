<?php

function migrationCategories() {
    $sql =  "CREATE TABLE IF NOT EXISTS public.categories
(
    id integer NOT NULL DEFAULT nextval('categories_id_seq'::regclass),
    category_name character varying(50) COLLATE pg_catalog.default,
    is_active smallint DEFAULT 1,
    CONSTRAINT categories_pkey PRIMARY KEY (id),
    CONSTRAINT isactive_check CHECK (is_active = ANY (ARRAY[0, 1]))
);";

    migration($sql);        
 }