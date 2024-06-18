<?php 
require_once(__DIR__ . '/productImage.service.php');
class ProductImagesController{
    public function create() {
        $product_id    = $_REQUEST['product_id'];
        ProductImagesService::create(files : getFiles(), product_id: $product_id);
    }
    public function show() {
        ProductImagesService::show(params: getRouteParams());
    }

    public function delete() {
         $product_id = getRouteParams('product_id');
         ProductImagesService::delete(product_id : $product_id);
    }   
}