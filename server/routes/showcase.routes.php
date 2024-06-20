<?php
    require_once($parentDir . '/module/showcaseModule/showcase.controller.php');

    $routes = new Route();
    $routes->useRoute('/showcase');
    $showcaseController =  new ShowcaseController();

    $routes->post('/create',   [$showcaseController, 'create'], 'isAuthenticated');
    $routes->get('/show',      [$showcaseController, 'show']);
    $routes->put('/update',    [$showcaseController, 'update'], 'isAuthenticated');
    $routes->delete('/delete', [$showcaseController, 'delete'], 'isAuthenticated');
?>