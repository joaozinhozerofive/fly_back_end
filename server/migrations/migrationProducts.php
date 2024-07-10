<?php 
function migrationProduct() {
    $sql = "CREATE TABLE IF NOT EXISTS public.products
(
    product_id integer NOT NULL DEFAULT nextval('products_id_seq'::regclass),
    product_name character varying COLLATE pg_catalog.default,
    product_description character varying COLLATE pg_catalog.default,
    subcategory_id integer,
    is_active smallint DEFAULT 1,
    product_price character varying(20) COLLATE pg_catalog.default NOT NULL,
    old_price character varying(20) COLLATE pg_catalog.default NOT NULL,
    CONSTRAINT products_pkey PRIMARY KEY (product_id),
    CONSTRAINT products_subcategory_id_fkey FOREIGN KEY (subcategory_id)
        REFERENCES public.subcategories (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
);";

    migration($sql);
 }

 function migrationProductImages() {
    $sql = "CREATE TABLE IF NOT EXISTS public.product_images
(
    image character varying COLLATE pg_catalog.default,
    id integer NOT NULL DEFAULT nextval('product_images_id_seq'::regclass),
    product_id integer,
    CONSTRAINT id_pkey PRIMARY KEY (id),
    CONSTRAINT product_id_fkey FOREIGN KEY (product_id)
        REFERENCES public.products (product_id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
);";

    migration($sql);
 }