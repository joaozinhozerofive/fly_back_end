<?php
    require_once('./utils/cors/index.php');
    require_once('./routes/index.php');
    require_once('./lib/useRoutes/index.php');
    require_once('./lib/useRequest/index.php');
    require_once('./utils/appError/index.php');
    require_once('./utils/utils/index.php');
    require_once('./utils/dataBase/index.php');
    require_once('./lib/qbquery/qbquery.php');
    require_once('./migrations/migrations.php');
    require_once('./middleware/authMiddleware.php');
   
    
    allowCors();
    // migrationsRun();
    
    $routes =  new Routes();
    $parentDir = dirname(__DIR__);
?>
