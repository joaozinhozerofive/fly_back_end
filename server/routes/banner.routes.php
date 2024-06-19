<?php
    require_once($parentDir . '/module/bannerModule/banner.controller.php');

    $routes = new Route();
    $routes->useRoute('/banner');
    $bannerController =  new BannerController();

    $routes->post('/create',   [$bannerController, 'create'], 'isAuthenticated');
    $routes->get('/show',      [$bannerController, 'show']);
    $routes->delete('/delete', [$bannerController, 'delete'], 'isAuthenticated');
?>