<?php
    require_once($parentDir . '/module/categoriesModule/categories.controller.php');

    $routes = new Route();
    $routes->useRoute('/categories');
    $categoriesController =  new CategoriesController();

    $routes->post('/create',   [$categoriesController, 'create']);
    $routes->get('/show',      [$categoriesController, 'show']);
    $routes->put('/update',    [$categoriesController, 'update']);
?>