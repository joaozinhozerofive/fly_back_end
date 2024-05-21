<?php


class DataBase {
    private $pgConnect;

    public function __construct() {
        
        try{
             $this->db_connect();

        }catch(Exception $e ) {
            throw new $e("Não foi possível estabelecer uma conexão com o banco de dados");
        }   
    }


    public function db_connect() {
        $db_host = getenv('DB_HOST');
        $db_user = getenv('DB_USER');
        $db_pass = getenv('DB_PASS');
        $db_port = getenv('DB_PORT');

        $this->pgConnect =  pg_connect("host=$db_host port=$db_port dbname=Fly user=$db_user password=$db_pass");
    }
}
?>
