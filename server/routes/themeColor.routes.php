<?php
    require_once($parentDir . '/module/themeColorModule/themeColor.controller.php');

    $routes = new Route();
    $routes->useRoute('/themeColor');
    $themeColorController =  new ThemeColorController();

    $routes->put('/update',    [$themeColorController, 'update'], 'isAuthenticated');
?>