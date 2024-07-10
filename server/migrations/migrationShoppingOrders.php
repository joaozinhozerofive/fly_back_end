<?php

function migrationShoppingOrdes() {
    $sql = "CREATE TABLE IF NOT EXISTS public.orders
(
    id integer NOT NULL DEFAULT nextval('shopping_orders_id_seq'::regclass),
    status integer DEFAULT 1,
    user_id integer,
    adress_id integer,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    total_price character varying(50) COLLATE pg_catalog.default,
    CONSTRAINT shopping_orders_pkey PRIMARY KEY (id),
    CONSTRAINT shopping_orders_adress_id_fkey FOREIGN KEY (adress_id)
        REFERENCES public.adress (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT shopping_orders_user_id_fkey FOREIGN KEY (user_id)
        REFERENCES public.users (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT shopping_orders_status_check CHECK (status = ANY (ARRAY[1, 2, 3, 4]))
);";

    migration($sql);
 }

 function migrationOrderProducts() {
    $sql = "CREATE TABLE IF NOT EXISTS public.order_products
(
    product_id integer,
    quantity integer,
    order_id integer,
    price character varying(50) COLLATE pg_catalog.default,
    CONSTRAINT oder_id_fkey FOREIGN KEY (order_id)
    REFERENCES public.orders (id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE
);";

    migration($sql);
 }