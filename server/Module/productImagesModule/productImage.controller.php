<?php 
require_once(__DIR__ . '/productImage.service.php');
class ProductImagesController{
    public function create() {
        $data    = requestFormData();
        ProductImagesService::create(files : getFiles(), product_id: $data->product_id);
    }
    public function show() {
        ProductImagesService::show(params: getRouteParams());
    }

    public function delete() {
         $product_id = getRouteParams('product_id');
         ProductImagesService::delete(product_id : $product_id);
    }   
}