<?php
    require_once($parentDir . '/module/adressModule/adress.controller.php');

    $routes = new Route();
    $routes->useRoute('/adress');
    $adressController =  new AdressController();

    $routes->post('/create',   [$adressController, 'create'], 'isAuthenticated');
    $routes->get('/show',      [$adressController, 'show'],   'isAuthenticated');
    $routes->put('/update',    [$adressController, 'update'], 'isAuthenticated');
    $routes->delete('/delete', [$adressController, 'delete'], 'isAuthenticated');
?>