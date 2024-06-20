<?php 
class Routes {

    public function __construct(){
        $parentDir = dirname(__DIR__);
        require_once($parentDir . '/routes/users.routes.php');
        require_once($parentDir . '/routes/sessions.routes.php');
        require_once($parentDir . '/routes/adress.routes.php');
        require_once($parentDir . '/routes/categories.routes.php');
        require_once($parentDir . '/routes/orders.routes.php');
        require_once($parentDir . '/routes/subcategories.routes.php');
        require_once($parentDir . '/routes/products.routes.php');
        require_once($parentDir . '/routes/productsImages.routes.php');
        require_once($parentDir . '/routes/banner.routes.php');
        require_once($parentDir . '/routes/files.routes.php');
        require_once($parentDir . '/routes/showcase.routes.php');
        require_once($parentDir . '/routes/themeColor.routes.php');
        require_once($parentDir . '/routes/usersHelp.routes.php');
    }
}
?>