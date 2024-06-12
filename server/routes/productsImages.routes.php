<?php
    require_once($parentDir . '/module/productImagesModule/productImage.controller.php');
    
    $routes = new Route();
    $routes->useRoute('/productsImages');
    $productImagesController =  new ProductImagesController();

    $routes->post('/create',   [$productImagesController, 'create']);
    $routes->get('/show',      [$productImagesController, 'show']);
    $routes->delete('/delete',    [$productImagesController, 'delete']);
?>