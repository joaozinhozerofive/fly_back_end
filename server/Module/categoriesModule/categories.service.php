<?php

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

    private static function getCategoriesByParams($params) {
        if(!empty($params['id'])) {
            $category = (new qbquery('categories'))
            ->where(['id' => $params['id']])
            ->getFirst();

            if(!$category) {
                AppError('Categoria não encontrada.', 404);
            }

            $subcategories = self::getSubcategoriesById($category->id);

            $subcategories = self::getArrayParentCategoryFormatted($subcategories);
            $category->subcategories = $subcategories;

            return $category;
        }

        if($params){ 
             $categories = (new qbquery('categories'))
            ->whereLike($params)
            ->getMany();

            $categoriesFormatted = []; 
            
            foreach($categories as $category) {
                $subcategories  = self::getSubcategoriesById($category['id']);
                $category['subcategories'] = self::getArrayParentCategoryFormatted($subcategories);
                $categoriesFormatted[] = $category;
            }

            return $categoriesFormatted;
        }

        $categories = (new qbquery('categories'))
        ->orderBy(['category_name ASC'])
        ->getMany();

        $categoriesFormatted = []; 

        foreach($categories as $category) {
            $subcategories  = self::getSubcategoriesById($category['id']);
            $category['subcategories'] = self::getArrayParentCategoryFormatted($subcategories);
            array_push($categoriesFormatted, $category);
        }

        return $categoriesFormatted;
    }

    private static function getDataCategoryUpdate($category, $data) {
        $category_name  = $data->category_name;
        $is_active      = $data->is_active;
        
        $category->category_name = trim($category_name ) ? $category_name : $category->category_name; 
        $category->is_active     = trim($is_active) == 1 || trim($is_active) == 0 ? $is_active : $category->is_active; 

        return $category;
    }

    private static function validateCategoryName($category_name) {
        if(!trim($category_name)) {
            AppError("Nome da categoria é obrigatório", 400);
        }

        if(strlen($category_name) >  50) {
            AppError("o Nome da categoria deve conter no máximo 50 caracteres", 400);
        }
    }

    private static function getArrayParentCategoryFormatted($subcategories) {
            $subcategoriesFormatted = [];

            foreach($subcategories as $subcategory) {
                $subcategory['parent_category'] = array_db_toPHP($subcategory['parent_category']);

                array_push($subcategoriesFormatted, $subcategory);
            }

            return $subcategoriesFormatted;;
    }

    private static function getSubcategoriesById($id) {
        return (new qbquery('subcategories'))
               ->whereArray(['parent_category' => $id])
               ->getMany();
    }
}