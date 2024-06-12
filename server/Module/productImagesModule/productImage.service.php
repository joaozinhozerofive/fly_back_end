<?php
require_once($parentDir . '/uploadsConfig/upload.php');
class ProductImagesService {
    public static function create($files, $product_id) {
        $product_image = property_exists($files, 'product_images') ?  $files->product_images : null;

        if(!$product_image) {
            AppError('É necessário inserir uma imagem para continuar', 400);
        }

        if(!trim($product_id)) {
            AppError("Informe o produto para continuar", 400);
        }

        $product = getData('products',[ 'id' => $product_id]);    

        if(!$product){
            AppError('Produto não encontrado', 404);
        }

        $newHash = bin2hex(random_bytes(16));
        $product_image->name = $newHash . $product_image->name;

        UploadConfigs::saveFile($files->product_images);

        try {
            $teste = (new qbquery('product_images'))
            ->insert([
                'image'      => $product_image->name, 
                'product_id' => $product_id
            ]);

            AppSucess("Imagem inserida com sucesso", 201);
        }
        catch(Exception $e) {
            AppError("Não foi possível inserir imagem para este produto.");
        }

    }

    public static function show($params) {
        responseStaticFile($params['image']);
    }

    public static function delete($product_id) {
        $product = getData('products', ['id' => $product_id]);
        $product_images = (new qbquery('product_images'))
        ->where(['product_id' => $product->id])
        ->getMany();
        
        if(!$product) {
            AppError("Produto não encontrado", 404);
        }

        if(!$product_images) {
            return;
        }

        try {
            foreach($product_images as $product_image) {
                (new qbquery('product_images'))
                ->delete("image = '".$product_image['image']."'");
                
                UploadConfigs::deleteFile($product_image['image']);
            }

        }
        catch(Exception $e) {
            AppError("Não foi possível deletar imagem do produto.");
        }
    }
}