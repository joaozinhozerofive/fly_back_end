<?php
    require_once($parentDir . '/module/adressModule/adress.controller.php');

    $routes = new Route();
    $routes->useRoute('/adress');
    $adressController =  new AdressController();

    $routes->post('/create',   [$adressController, 'create']);
    $routes->get('/show',      [$adressController, 'show']);
    $routes->put('/update',    [$adressController, 'update']);
    $routes->delete('/delete', [$adressController, 'delete']);
?>