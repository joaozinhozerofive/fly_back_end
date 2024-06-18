<?php
    require_once($parentDir . '/module/ordersModule/orders.controller.php');

    $routes = new Route();
    $routes->useRoute('/orders');
    $ordersController =  new OrdersController();

    $routes->post('/create',   [$ordersController, 'create'], 'isAuthenticated');
    $routes->get('/show',      [$ordersController, 'show'],   'isAuthenticated');
    $routes->put('/update',    [$ordersController, 'update'], 'isAuthenticated');
?>