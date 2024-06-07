<?php
    require_once($parentDir . '/module/subcategoriesModule/subcategories.controller.php');

    $routes = new Route();
    $routes->useRoute('/subcategories');
    $subcategoriesController =  new SubcategoriesController();

    $routes->post('/create',   [$subcategoriesController, 'create']);
    $routes->get('/show',      [$subcategoriesController, 'show']);
    $routes->put('/update',    [$subcategoriesController, 'update']);
?>