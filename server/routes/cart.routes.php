<?php
    require_once($parentDir . '/module/cartModule/cart.controller.php');

    $routes = new Route();
    $routes->useRoute('/cart');
    $cartController =  new CartController();

    $routes->post('/create',   [$cartController, 'create'],    'isAuthenticated');
    $routes->get('/show',      [$cartController, 'show'],      'isAuthenticated');
    $routes->put('/update',    [$cartController, 'update'],    'isAuthenticated');
    $routes->delete('/delete', [$cartController, 'delete'],    'isAuthenticated');
?>