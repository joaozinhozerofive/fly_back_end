<?php 

function migrationUsersHelp() {
    $sql = "CREATE TABLE IF NOT EXISTS public.users_help
(
    id integer NOT NULL DEFAULT nextval('users_help_id_seq'::regclass),
    user_name character varying(100) COLLATE pg_catalog.default,
    email character varying(254) COLLATE pg_catalog.default,
    description character varying COLLATE pg_catalog.default,
    CONSTRAINT users_help_pkey PRIMARY KEY (id)
);";

    migration($sql);
 }