<?php

require_once(__DIR__ . '/migrationAdress.php');
require_once(__DIR__ . '/migrationBanner.php');
require_once(__DIR__ . '/migrationCategories.php');
require_once(__DIR__ . '/migrationProducts.php');
require_once(__DIR__ . '/migrationShoppingCart.php');
require_once(__DIR__ . '/migrationShoppingOrders.php');
require_once(__DIR__ . '/migrationShowCase.php');
require_once(__DIR__ . '/migrationShowCaseBanner.php');
require_once(__DIR__ . '/migrationSubCategories.php');
require_once(__DIR__ . '/migrationThemeColor.php');
require_once(__DIR__ . '/migrationUsersHelp.php');
require_once(__DIR__ . '/migrationUsers.php');

 function migrationsRun() {
    migrationThemeColor();
    migrationsUsers();
    migrationAdress();
    migrationCategories();
    migrationSubCategories();
    migrationProduct();
    migrationProductImages();
    migrationShowCase();
    migrationBanner();
    migrationshowcaseBanner();
    migrationShooppingCart();
    migrationShoppingOrdes();
    migrationOrderProducts();
    migrationUsersHelp();
 }

 function migration($sql) {
    $connection =  new DataBaseConnection();
    try{
        $result = pg_query($connection->getConnection(), $sql);

        if(!$result) {
            throw new Exception("Erro ao executar a instrução no banco de dados");
        }

        return pg_affected_rows($result);
    }
    catch(Exception){
        throw new Exception("Não foi possível executar transação no banco de dados");
    }
 }