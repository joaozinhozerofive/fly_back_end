<?php
    require_once($parentDir . '/module/filesModule/files.controller.php');

    $routes = new Route();
    $routes->useRoute('/files');
    $filesController =  new FilesController();

    $routes->get('/show',      [$filesController, 'show']);
?>