<?php 
class Routes {

    public function __construct(){
        $parentDir = dirname(__DIR__);
        require_once($parentDir . '/routes/users.routes.php');
    }
   
}
?>