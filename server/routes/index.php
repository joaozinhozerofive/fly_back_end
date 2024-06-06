<?php 
class Routes {

    public function __construct(){
        $parentDir = dirname(__DIR__);
        require_once($parentDir . '/routes/users.routes.php');
        require_once($parentDir . '/routes/sessions.routes.php');
        require_once($parentDir . '/routes/adress.routes.php');
        require_once($parentDir . '/routes/categories.routes.php');
        require_once($parentDir . '/routes/orders.routes.php');
    }
}
?>