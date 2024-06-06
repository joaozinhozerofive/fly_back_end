<?php
    require_once($parentDir . '/module/sessionsModule/sessions.controller.php');

    $routes = new Route();
    $routes->useRoute('/sessions');
    $sessionsController =  new SessionsController();

    $routes->post('/', [$sessionsController, 'create']);
?>