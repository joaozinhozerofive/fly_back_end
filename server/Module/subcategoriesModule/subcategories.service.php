<?php
class SubcategoriesService {
    public static function create($data) {
        $subcategory_name = $data->subcategory_name;
        $parent_category  = $data->parent_category;
        $parent_category  = array_php_toDb($parent_category);

        self::validateDataSubcategoryCreate($data);

        try{
            (new qbquery('subcategories'))
            ->insert([
                'subcategory_name' => $subcategory_name, 
                'parent_category'  => $parent_category
            ]);
            
            AppSucess("Subcategoria criada com sucesso!", 201);

        }catch(Exception $e) {
            AppError("Não foi possível criar subcategoria.");
        }
    }

    public static function show($params) {
        $subcategories = self::getSubcategoriesByParams($params);

        return $subcategories;
    }

    public static function update($id, $data) {
        $subcategory = getData('subcategories', ['id' => $id]);
        
        if(!$subcategory) {
            AppError("Subcategoria não encontrada.", 404);
        }
     
        $subcategory = self::getDataSubcategoryUpdate($subcategory, $data);

        self::validateDataSubcategoryUpdate($subcategory);

        $subcategory->parent_category = array_php_toDb($subcategory->parent_category);

        try{
            (new qbquery('subcategories'))
            ->update($subcategory, ['id' =>$id]);

            AppSucess("Subcategoria atualizada com sucesso!");

        }catch(Exception $e) {
            AppError("Não foi possível atualizar subcategoria.", 500);
        }
    }

    public static function validateDataSubcategoryCreate($data) {  
        $subcategory_name = $data->subcategory_name;
        $parent_category  = $data->parent_category;

        self::validateSubCategoryName($subcategory_name);

        if(!$parent_category) {
            AppError("É necessário informar a categoria PAI da subcategoria que você deseja cadastrar.", 400);
        }


        foreach($parent_category as $parent) {
            $category = (new qbquery('categories'))
            ->where(['id' => $parent])
            ->getFirst();

            if(!$category){
                AppError("Categoria PAI (id:{$parent}) não encontrada.", 404);
            }
        }

    }

    public static function validateDataSubcategoryUpdate($subcategory) {
        foreach($subcategory->parent_category as $parent){
            $category = (new qbquery('categories'))
            ->where(['id' => $parent])
            ->getFirst();

            if(!$category) {
                AppError("Categoria PAI (id:{$parent}) não encontrada.", 404);
            }
        }

        if($subcategory->is_active != 0 && $subcategory->is_active != 1) {
            AppError("Não foi encontrado valor ($subcategory->is_active) correspondente para o status da subcategoria ", 400);
        }

        self::validateSubCategoryName($subcategory->subcategory_name);
    }

    public static function getSubcategoriesByParams($params) {
        if(!empty($params['id'])) {
            $subcategory = self::getSubcategoryById($params['id']);

            if(!$subcategory) {
                AppError('Subcategoria não encontrada.', 404);
            }

            $subcategory->parent_category = array_db_toPHP($subcategory->parent_category);

            return $subcategory;
        }

        if($params){ 

            if($params['parent_category']) {
                return self::getSubCategoriesByParentCategory($params['parent_category']);
            }

            return self::getSubcategoriesByLikeParams($params);
        }

        $subcategories = self::getManySubcategories();

        return $subcategories;
    }

    public static function getDataSubcategoryUpdate($subcategory, $data) { 
        $subcategory_name = $data->subcategory_name;
        $parent_category  = $data->parent_category;
        $is_active        = $data->is_active;

        $subcategory->subcategory_name = trim($subcategory_name) ? $subcategory_name : $subcategory->subcategory_name;
        $subcategory->parent_category  = $parent_category        ? $parent_category  : $subcategory->parent_category;
        $subcategory->is_active        = trim($is_active) == 1 || trim($is_active) == 0 ? $is_active : $subcategory->is_active; 

        return $subcategory;
    }


    public static function validateSubCategoryName($subcategory_name) {
        if(!trim($subcategory_name)) {
            AppError("Nome da subcategoria é obrigatório", 400);
        }
        if(strlen($subcategory_name) >  50) {
            AppError("o Nome da subcategoria deve conter no máximo 50 caracteres", 400);
        }
    }

    public static function getSubCategoriesByParentCategory($parent_category) {
        return (new qbquery('subcategories'))
        ->whereArray(['parent_category' => $parent_category])
        ->getMany();
    }

    public static function getSubcategoryById($id) {
        return (new qbquery('subcategories'))
        ->where(['id' => $id])
        ->getFirst();
    }

    public static function getSubcategoriesByLikeParams($params) {
        return (new qbquery('subcategories'))
        ->whereLike($params)
        ->getMany();
    }

    public static function getManySubcategories() {
        $subcategories =  (new qbquery('subcategories'))
        ->orderBy(['subcategory_name ASC'])
        ->getMany();

        $newSubcategories = [];

        foreach($subcategories as $subcategory) {
            $subcategory['parent_category'] = array_db_toPHP($subcategory['parent_category']);
            array_push($newSubcategories, $subcategory);
        }

        return $newSubcategories ;
    }
}