<?php 

function migrationsUsers() {
    $sql = "CREATE TABLE IF NOT EXISTS public.users
(
    id integer NOT NULL DEFAULT nextval('users_id_seq'::regclass),
    username character varying(100) COLLATE pg_catalog.default NOT NULL,
    email character varying(254) COLLATE pg_catalog.default NOT NULL,
    privilege integer NOT NULL,
    gender integer,
    phone character varying(20) COLLATE pg_catalog.default,
    birth character varying(10) COLLATE pg_catalog.default,
    cpf character varying(15) COLLATE pg_catalog.default,
    password character varying(500) COLLATE pg_catalog.default,
    is_active smallint DEFAULT 1,
    CONSTRAINT users_pkey PRIMARY KEY (id),
    CONSTRAINT users_email_key UNIQUE (email),
    CONSTRAINT users_gender_check CHECK (gender = ANY (ARRAY[0, 1, 2]))
);";
    
    migration($sql);
 }