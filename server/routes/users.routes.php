<?php 
require_once($parentDir .  '/Module/UsersModule/users.controller.php');


$routes = new Route();
$usersController =  new UsersController;

$routes->useRoute('/users');
$routes->get('/teste', $usersController->show());
$routes->post('/teste', $usersController->create());

?>