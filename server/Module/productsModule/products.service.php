<?php 

class ProductsService{
    public static function create($data) {
        $product_name         = $data->product_name;
        $product_price        = $data->product_price;
        $product_description  = $data->product_description;
        $old_price            = $data->old_price;
        $subcategory_id       = $data->subcategory_id;

        self::validateProductsCreate($data);

        try {
            (new qbquery('products'))
            ->insert([
                'product_name'         => $product_name,
                'product_price'        => $product_price,
                'product_description'  => $product_description,
                'old_price'            => $old_price,
                'subcategory_id'       => $subcategory_id
            ]);

            AppSucess("Produto criado com sucesso!", 201);
        }
        catch(Exception $e) {
            AppError("Não foi possível criar produto");
        }

        response($data);
    }

    public static function show($params) {
        
    }

    public static function update($id, $data) {

    }

    public static function delete($id) {

    }

    private static function validateProductsCreate($data) {
        $product_name         = $data->product_name;
        $product_price        = $data->product_price;
        $product_description  = $data->product_description;
        $old_price            = $data->old_price;
        $subcategory_id       = $data->subcategory_id;

        if(!trim($product_name)) {
            AppError("Nome do produto é obrigatório.", 400);
        }

        if(strlen($product_name) < 10) {
            AppError("Nome do produto deve ter no mínimo 10 caracteres.", 400);
        }

        if(!trim($product_price)) {
            AppError("Preço do produto é obrigatório.", 400);
        }

        if(!trim($product_description)) {
            AppError("Descrição do produto é obrigatória.", 400);
        }

        if(!trim($old_price)) {
            AppError("Preço antigo é obrigatório");
        }

        if(!trim($subcategory_id)) {
            AppError("Subcategoria é obrigatória", 400);
        }

        $subcategory = getData('subcategories', ['id' => $subcategory_id]);

        if(!$subcategory) {
            AppError("Subcategoria não encontrada", 404);
        }
    }
    
    private static function getDataUpdateProduct($products, $data) {

    }


}