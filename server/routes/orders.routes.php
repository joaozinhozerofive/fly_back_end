<?php
    require_once($parentDir . '/module/ordersModule/orders.controller.php');

    $routes = new Route();
    $routes->useRoute('/orders');
    $ordersController =  new OrdersController();

    $routes->post('/create',   [$ordersController, 'create']);
    $routes->get('/show',      [$ordersController, 'show']);
    $routes->put('/update',    [$ordersController, 'update']);
?>