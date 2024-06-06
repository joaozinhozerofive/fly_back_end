<?php
    require_once($parentDir . '/module/usersModule/users.controller.php');

    $routes = new Route();
    $routes->useRoute('/users');
    $usersController =  new UsersController();

    $routes->post('/create',   [$usersController, 'create']);
    $routes->get('/show',      [$usersController, 'show']);
    $routes->put('/update',    [$usersController, 'update']);
    $routes->delete('/delete', [$usersController, 'delete']);
    
?>