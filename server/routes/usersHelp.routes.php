<?php
    require_once($parentDir . '/module/usersHelpModule/usersHelp.controller.php');

    $routes = new Route();
    $routes->useRoute('/usersHelp');
    $usersHelpController =  new UsersHelpController();
    
    $routes->post('/create',  [$usersHelpController, 'create']);
?>