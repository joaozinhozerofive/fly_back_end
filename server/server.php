<?php
    require_once('./services/cors/index.php');
    require_once('./routes/index.php');
    require_once('./lib/useRoutes/index.php');
    require_once('./lib/useRequest/index.php');
    require_once('./services/appError/index.php');
    require_once('./services/utils/index.php');
    require_once('./services/dataBase/index.php').
    
    allowCors();
    // $routes = new Routes();
    (new DataBase());
    $parentDir = dirname(__DIR__);
?>
