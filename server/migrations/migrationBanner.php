<?php 

function migrationBanner() {
    $sql = "CREATE TABLE IF NOT EXISTS public.banner
(
    id integer NOT NULL DEFAULT nextval('banner_id_seq'::regclass),
    page_name character varying(350) COLLATE pg_catalog.default,
    image character varying COLLATE pg_catalog.default NOT NULL,
    banner_title character varying(100) COLLATE pg_catalog.default,
    CONSTRAINT banner_pkey PRIMARY KEY (id)
);";

    migration($sql);
 }