<?php 
require_once(dirname(__DIR__) .'/productImagesModule/productImage.service.php');
require_once(dirname(__DIR__) .'/subcategoriesModule/subcategories.service.php');
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

        responseJson($data);
    }

    public static function show($params) {
        $products = self::getProductsByRouteParams($params);

        return $products;
    }

    public static function update($id, $data) {
        $product = getData('products', ['product_id' => $id]);

        if(!trim($id)) {
            AppError("Informe um id para continuar");
        }

        if(!$product) {
            AppError("Produto não encontrado", 404);
        }

        $product = self::getDataProductUpdate($product, $data);
        $subcategory = SubcategoriesService::getSubcategoryById($product->subcategory_id);

        if(!$subcategory) {
            AppError("Subcategoria não encontrada.", 404);
        }

        try {
            (new qbquery('products'))
            ->update($product, ['product_id' => $id]);

            AppSucess('Produto alterado com sucesso!');
        }
        catch (Exception $e) {
            AppError("Não foi possível atualizar produto.", 400);
        }
    }

    public static function validateProductsCreate($data) {
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

    public static function getProductsByRouteParams($params) {
        $params['id'] = intval($params['id']);

        if(!empty($params['id'])) {
            $products = self::getProductById($params['id']);
            
            return $products;
        }

        if($params) {
            return self::getProductsByLikeParams($params);
        }

        return self::getManyProducts();
    }
    
    public static function getDataUpdateProduct($products, $data) {

    }

    public static function getProductById($id) {
        $product = (new qbquery('products'))
        ->where(['products.product_id' => $id])
        ->innerJoin('subcategories', ['subcategories.id' => 'products.subcategory_id'])
        ->getFirst(['parent_category', 'id']);

        if(!$product) {
                AppError("Produto não encontrado", 404);
        }

        $product_images = ProductImagesService::getProductImagesByProductId($product->product_id);

        $product->images = $product_images;

        return $product;
    }

    public static function getProductsByLikeParams($params){
        $products = (new qbquery('products'))
        ->whereLike($params)
        ->innerJoin('subcategories', ['subcategories.id' => 'products.subcategory_id'])
        ->getMany(null, ['parent_category', 'id']);

        $newProducts = []; 

        foreach($products as $product) {
            $product_images    = ProductImagesService::getProductImagesByProductId($product['product_id']);
            $product['images'] = $product_images;
            array_push($newProducts, $product);
        }

        return $products;
    }

    public static function getManyProducts() {
        $products =  (new qbquery('products'))
        ->getMany();

        $newProducts = []; 

        foreach($products as $product) {
            $product_images    = ProductImagesService::getProductImagesByProductId($product['product_id']);
            $product['images'] = $product_images;
            array_push($newProducts, $product);
        }

        return $newProducts;
    }

    public static function getDataProductUpdate($product, $data) {
        $product_name        = $data->product_name;
        $product_description = $data->product_description;
        $subcategory_id      = $data->subcategory_id;
        $product_price       = $data->product_price;  
        $old_price           = $data->old_price;
        $is_active           = $data->is_active;

        $product->product_name        = trim($product_name)        ? $product_name        : $product->product_name; 
        $product->product_description = trim($product_description) ? $product_description : $product->product_description;
        $product->subcategory_id      = trim($subcategory_id)      ? $subcategory_id      : $product->subcategory_id;
        $product->product_price       = trim($product_price)       ? $product_price       : $product->product_price;
        $product->old_price           = trim($old_price)           ? $old_price           : $product->old_price;
        $product->is_active           = trim($is_active)           ? $is_active           : $product->is_active;
        $product->is_active           = trim($is_active) == 1 || trim($is_active) == 0 ? $is_active : $product->is_active;
        
        return $product;
    }

    

}