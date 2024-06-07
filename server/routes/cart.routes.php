<?php
    require_once($parentDir . '/module/cartModule/cart.controller.php');

    $routes = new Route();
    $routes->useRoute('/cart');
    $cartController =  new CartController();

    $routes->post('/create',   [$cartController, 'create']);
    $routes->get('/show',      [$cartController, 'show']);
    $routes->put('/update',    [$cartController, 'update']);
    $routes->delete('/delete',    [$cartController, 'delete']);
?>