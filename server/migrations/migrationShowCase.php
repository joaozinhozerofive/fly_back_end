<?php 
function migrationShowCase() {
    $sql = "CREATE TABLE IF NOT EXISTS public.showcase
(
    showcase_name character varying(100) COLLATE pg_catalog.default,
    page_name character varying(350) COLLATE pg_catalog.default,
    is_active integer,
    id integer NOT NULL DEFAULT nextval('showcase_id_seq'::regclass),
    CONSTRAINT showcase_pkey PRIMARY KEY (id),
    CONSTRAINT is_active_ck CHECK (is_active = ANY (ARRAY[0, 1]))
);";
    
    migration($sql);
 }