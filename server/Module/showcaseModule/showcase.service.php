<?php
require_once(dirname(__DIR__) . '/productsModule/products.service.php');
class ShowcaseService {
    public static function create($data) {
        $is_active     = trim($data->is_active) ? $data->is_active : 0;
        $showcase_name = $data->showcase_name;
        $page_name     = $data->page_name;
        $products_id   = $data->products_id;    

        self::validateDataShowcaseCreate($data);

        $products = [];

        foreach($products_id as $product_id) {
            $product = ProductsService::getProductById($product_id); 
            array_push($products, $product);
        }

        try {
            $showcase = (new qbquery('showcase'))
            ->insert([
                'showcase_name' => $showcase_name, 
                'page_name'     => $page_name,
                'is_active'     => $is_active
            ]);

            foreach($products as $product) {
                (new qbquery('showcase_products'))
                ->insert([
                    'showcase_id' => $showcase['id'],
                    'product_id'  => $product->product_id
                ]);
            }

            AppSucess("Vitrine criada com sucesso!", 201);
        }
        catch (Exception $e) {
            AppError("Não foi possível crirar vitrine.", 400);
        }

        responseJson($data);
    }


    public static function show() {
        
    }

    public static function update() {

    }

    public static function delete() {
        
    }

    public static function validateDataShowcaseCreate($data) {
        $is_active     = trim($data->is_active) ? $data->is_active : 0;
        $showcase_name = $data->showcase_name;
        $page_name     = $data->page_name;
        $products_id   = $data->products_id;   

        if(!trim($showcase_name)) {
            AppError("Nome da Vitrine é obrigatório.", 401);
        }

        if(!trim($page_name)) {
            AppError("Página da Vitrine é obrigatório.", 401);
        }

        if(!$products_id) {
            AppError("É necessário informar ao menos um produto para continuar", 401);
        }      
    }
}