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
                self::insertShowcaseProducts($showcase['id'], $product->product_id);
            }

            AppSucess("Vitrine criada com sucesso!", 201);
        }
        catch (Exception $e) {
            AppError("Não foi possível crirar vitrine.", 400);
        }

        responseJson($data);
    }


    public static function show($params) {
         $showcase = self::getShowcaseByRouteParams($params);

         return $showcase;
    }

    public static function update($id, $data) {
        $showcase = self::getShowcaseById($id);

        if(!$showcase) {
            AppError("Vitrine não encontrada." , 404);
        }

        $showcase    = self::getDataVitrineUpdate($showcase, $data);
        $products_id = $showcase->products;
        unset($showcase->products);

        if(strlen($showcase->showcase_name) > 50) {
            AppError("Nome da vitrine deve ter no máximo 50 carácteres.", 400);
        }

        try {
           (new qbquery('showcase'))
            ->update($showcase, ['id' => $id]);

            self::deleteShowcaseProductsByShowcaseId($id);


            foreach($products_id as $product_id) {
                self::insertShowcaseProducts($id, $product_id);
            }

            AppSucess("Vitrine alterada com sucesso!");
        }
        catch(Exception) {
            AppError("Não foi possível atualizar esta Vitrine.", 404);
        }

        responseJson($showcase);
    }

    public static function validateDataShowcaseCreate($data) {
        $showcase_name = $data->showcase_name;
        $page_name     = $data->page_name;
        $products_id   = $data->products_id;   

        if(strlen($showcase_name) > 50) {
            AppError("Nome da vitrine deve ter no máximo 50 carácteres.", 400);
        }

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


    public static function getShowcaseByRouteParams($params) {
        $id = intval(pr_value($params, 'id'));

        if($id) {
            return self::getShowcaseById($id);
        }

        if($params) {
            return self::getShowcaseByLikeParams($params);
        }

        return self::getManyShowcases();
    }

    public static function getShowcaseById($id) {
        $showcase = (new qbquery('showcase'))
        ->where([
            'id' => $id
        ])
        ->getFirst();

        if(!$showcase) {
            AppError("Vitrine não encontrada.", 404);
        }

        $showcaseProducts = (new qbquery('showcase_products'))
        ->where([
            'showcase_id' => $showcase->id
        ])
        ->getMany();

        $products = [];

        foreach($showcaseProducts as $showcaseProduct) {
            $product = ProductsService::getProductById($showcaseProduct['product_id']);

            array_push($products, $product);
        }   

        $showcase->products = $products;

        return $showcase;
    }

    public static function getShowcaseByLikeParams($params) {
        $params = objectToArrayAssoc($params);

        $showcases = (new qbquery('showcase'))
        ->whereLike($params)
        ->getMany();

        $newShowCase = [];

        foreach($showcases as $showcase) {
            $showCaseProducts = (new qbquery('showcase_products'))
            ->where([
                'showcase_id' => $showcase['id']
            ])
            ->innerJoin('products', ['products.product_id' => 'showcase_products.product_id'])
            ->getMany();

            $showcase['products'] = $showCaseProducts;

            array_push($newShowCase, $showcase);
        }

        $showcase = [];

        return $newShowCase;
    }

    public static function getManyShowcases() {
        $showcases = (new qbquery('showcase'))
        ->getMany();

        $newShowCase = [];

        foreach($showcases as $showcase) {
            $showCaseProducts = (new qbquery('showcase_products'))
            ->where([
                'showcase_id' => $showcase['id']
            ])
            ->innerJoin('products', ['products.product_id' => 'showcase_products.product_id'])
            ->getMany();

            $showcase['products'] = $showCaseProducts;

            array_push($newShowCase, $showcase);
        }

        $showcase = [];

        return $newShowCase;
    }

    public static function getDataVitrineUpdate($showcase, $data) {
        $showcase_name    = $data->showcase_name;
        $page_name        = $data->page_name;
        $products_id      = $data->products_id;
        $is_active        = $data->is_active;


        $showcase->products        = $products_id;
        $showcase->showcase_name   = trim($showcase_name) ? $showcase_name : $showcase->showcase_name;
        $showcase->page_name       = $page_name           ? $page_name     : $showcase->page_name;
        $showcase->is_active       = trim($is_active) == 1 || trim($is_active) == 0 ? $is_active : $showcase->is_active; 

        return $showcase;
    }


    public static function deleteShowcaseProductsByShowcaseId($showcaseId) {
        (new qbquery('showcase_products'))
        ->delete("showcase_id = $showcaseId");
    }

    public static function insertShowcaseProducts($showcaseId, $product_id) {
        (new qbquery('showcase_products'))
        ->insert([
            'showcase_id' => $showcaseId, 
            'product_id'  => $product_id
        ]);
    }

}