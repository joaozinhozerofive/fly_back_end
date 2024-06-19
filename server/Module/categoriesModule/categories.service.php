<?php
require_once(dirname(__DIR__) . '/subcategoriesModule/subcategories.service.php');

class CategoriesService{
    public static function create($data) {
        $category_name = $data->category_name;
        
        self::validateCategoryName($category_name);
        
        try{
            (new qbquery('categories'))
            ->insert([
                'category_name' => $category_name
            ]);
            
             AppSucess("Categoria criada com sucesso!", 201);

        }catch(Exception $e) {
            AppError("Não foi possível criar categoria.");
        }
    }

    public static function show($params) {
        $categories = self::getCategoriesByParams($params);

        return $categories;
    }

    public static function update($id, $data) {
        if(!trim($id)) {
            AppError('Informe o id da categoria para continuar', 400);
        }

        $category = getData('categories' , ['id' => $id]);

        if(!$category){
            AppError('Categoria não encontrada', 404);
        }

        $category =  self::getDataCategoryUpdate($category, $data);

        self::validateCategoryName($category->category_name);

        try{
            (new qbquery('categories'))
            ->update($category, ['id' => $id]);

            AppSucess('Categoria atualizada com sucesso!');
        }
        catch(Exception $e) {
            AppError("Não foi possível atualizar categoria.", 400);
        }

    }

    public static function getCategoriesByParams($params) {
        $id = intval(pr_value($params, 'id'));

        if($id) {
            $category = (new qbquery('categories'))
            ->where(['id' => $id])
            ->getFirst();

            if(!$category) {
                AppError('Categoria não encontrada.', 404);
            }

            $subcategories = SubcategoriesService::getSubCategoriesByParentCategory($category->id);

            $subcategories = self::getArrayParentCategoryFormatted($subcategories);
            $category->subcategories = $subcategories;

            return $category;
        }

        if($params){ 
            $categories = self::getCategoriesByLikeParams($params);

            return $categories;
        }

        return self::getManyCategories();
    }

    public static function getDataCategoryUpdate($category, $data) {
        $category_name  = $data->category_name;
        $is_active      = $data->is_active;
        
        $category->category_name = trim($category_name ) ? $category_name : $category->category_name; 
        $category->is_active     = trim($is_active) == 1 || trim($is_active) == 0 ? $is_active : $category->is_active; 

        return $category;
    }

    public static function validateCategoryName($category_name) {
        if(!trim($category_name)) {
            AppError("Nome da categoria é obrigatório", 400);
        }

        if(strlen($category_name) >  50) {
            AppError("o Nome da categoria deve conter no máximo 50 caracteres", 400);
        }
    }

    public static function getArrayParentCategoryFormatted($subcategories) {
            $subcategoriesFormatted = [];

            foreach($subcategories as $subcategory) {
                $subcategory['parent_category'] = array_db_toPHP($subcategory['parent_category']);

                array_push($subcategoriesFormatted, $subcategory);
            }

            return $subcategoriesFormatted;;
    }

    public static function getCategoriesByLikeParams($params) {
        $params = objectToArrayAssoc($params);

        $categories = (new qbquery('categories'))
        ->whereLike($params)
        ->getMany();

        $categoriesFormatted = []; 
            
            foreach($categories as $category) {
                $subcategories  = SubcategoriesService::getSubCategoriesByParentCategory($category['id']);
                $category['subcategories'] = self::getArrayParentCategoryFormatted($subcategories);
                $categoriesFormatted[] = $category;
            }

        return $categoriesFormatted;
    }

    public static function getManyCategories() {
        $categories = (new qbquery('categories'))
        ->orderBy(['category_name ASC'])
        ->getMany();

        $categoriesFormatted = []; 

        foreach($categories as $category) {
            $subcategories  = SubcategoriesService::getSubCategoriesByParentCategory($category['id']);
            $category['subcategories'] = self::getArrayParentCategoryFormatted($subcategories);
            array_push($categoriesFormatted, $category);
        }

        return $categoriesFormatted;
    }
}