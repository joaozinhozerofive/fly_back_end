<?php 

function migrationSubCategories() {
    $sql =  "CREATE TABLE IF NOT EXISTS public.subcategories
(
    id integer NOT NULL DEFAULT nextval('subcategories_id_seq'::regclass),
    subcategory_name character varying(50) COLLATE pg_catalog.default,
    parent_category integer[],
    is_active smallint DEFAULT 1,
    CONSTRAINT subcategories_pkey PRIMARY KEY (id),
    CONSTRAINT isactive_check CHECK (is_active = ANY (ARRAY[0, 1]))
);";

    migration($sql); 
 }