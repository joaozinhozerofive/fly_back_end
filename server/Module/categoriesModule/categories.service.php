<?php

class CategoriesService{
    public static function create($data) {
        $category_name = $data->category_name;
        
        self::validateCategoryName($data);
        
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

        $categorie = getData('categories' , ['id' => $id]);

        if(!$categorie){
            AppError('Categoria não encontrada', 404);
        }

        $categorie =  self::getDataCategorieUpdate($categorie, $data);

        self::validateCategoryName($categorie->category_name);

        try{
            (new qbquery('categories'))
            ->update($categorie, ['id' => $id]);

            AppSucess('Categoria atualizada com sucesso!');
        }
        catch(Exception $e) {
            AppError("Não foi possível atualizar categoria.", 400);
        }

    }

    public static function delete($id) {
        if(!trim($id)){
            AppError('Informe o id da categoria para continuar', 400);
        }

        $categorie = getData('categories', ['id' => $id]);

        if(!$categorie){
            AppError("Categoria não encontrada", 404);
        }

        try{
            (new qbquery('categories'))->delete("id = $id");
            
            AppSucess('Categoria excluída com sucesso!');
        }
        catch(Exception $e){
            AppError('Não foi possível excluir esta categoria.');
        }
    }

    private static function getCategoriesByParams($params) {
        if(!empty($params['id'])) {
            $categorie = getData('categories', ['id' => $params['id']]);

            if(!$categorie) {
                AppError('Categoria não encontrada.', 404);
            }

            return $categorie;
        }

        if($params){ 
            return (new qbquery('categories'))
            ->whereLike($params)
            ->getMany();
        }

        $categories = (new qbquery('categories'))
        ->orderBy(['category_name ASC'])
        ->getMany();

        return $categories;
    }

    private static function getDataCategorieUpdate($categorie, $data) {
        $category_name  = $data->category_name;
        
        $categorie->category_name = trim($category_name ) ? $category_name : $categorie->category_name; 

        return $categorie;
    }

    private static function validateCategoryName($category_name) {
        if(!trim($category_name)) {
            AppError("Nome da categoria é obrigatório", 400);
        }
        if(strlen($category_name) >  50) {
            AppError("o Nome da categoria deve conter no máximo 50 caracteres", 400);
        }
    }
}