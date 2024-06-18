<?php
    require_once($parentDir . '/module/productsModule/products.controller.php');
    
    $routes = new Route();
    $routes->useRoute('/products');
    $productsController =  new ProductsController();

    $routes->post('/create',   [$productsController, 'create'], 'isAuthenticated');
    $routes->get('/show',      [$productsController, 'show']);
    $routes->put('/update',    [$productsController, 'update'], 'isAuthenticated');
?>