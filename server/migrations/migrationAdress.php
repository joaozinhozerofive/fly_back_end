<?php

function migrationAdress() {
    $sql = "CREATE TABLE IF NOT EXISTS public.adress
(
    id integer NOT NULL DEFAULT nextval('adress_id_seq'::regclass),
    user_id integer,
    street_adress character varying(100) COLLATE pg_catalog.default,
    residence_code character varying(50) COLLATE pg_catalog.default,
    country character varying(60) COLLATE pg_catalog.default,
    city character varying(60) COLLATE pg_catalog.default,
    district character varying(60) COLLATE pg_catalog.default,
    state character varying(5) COLLATE pg_catalog.default,
    cep character varying(15) COLLATE pg_catalog.default,
    complement character varying(60) COLLATE pg_catalog.default,
    CONSTRAINT adress_pkey PRIMARY KEY (id),
    CONSTRAINT adress_user_id_fkey FOREIGN KEY (user_id)
    REFERENCES public.users (id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE
);";
    
    migration($sql);
 }