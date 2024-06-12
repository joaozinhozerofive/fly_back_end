<?php

class DataBaseConnection {
    public static $pgConnect;

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
        try{
         self::$pgConnect =  pg_connect("host=$db_host port=$db_port dbname=Fly user=$db_user password=$db_pass");
        }catch(Exception $e) {
            throw new Exception("Não foi possível estabelecer uma conexão com o banco de dados");
        }
        
    }

    public function db_disconnect(){
        if(self::$pgConnect) {
            pg_close(self::$pgConnect);
        }
    }

    public function fetch_data($sSql){
        $query = pg_query(self::$pgConnect, $sSql);
        $data  = pg_fetch_all($query); 

        return $data;
    }

    public function getConnection() {
        return self::$pgConnect;
    }

    public function insert($query) {
        try{
            $result = pg_query(self::$pgConnect, $query);

            if(!$result) {
                throw new Exception("Erro ao executar a instrução de inserção no banco de dados");
            }

             pg_affected_rows($result);
             return pg_fetch_all($result)[0];
        }
        catch(Exception){
            throw new Exception("Não foi possível inserir os dados no banco de dados");
        }
    }

    public function update($query) {
        try{
            $result = pg_query(self::$pgConnect, $query);

            if(!$result) {
                throw new Exception("Erro ao executar a instrução de atualização no banco de dados");
            }

            return pg_affected_rows($result);
        }
        catch(Exception){
            throw new Exception("Não foi possível inserir os dados no banco de dados");
        }
    }

    public function delete($query) {
        try{
            $result = pg_query(self::$pgConnect, $query);

            if(!$result) {
                throw new Exception("Erro ao executar a instrução de atualização no banco de dados");
            }

            return pg_affected_rows($result);
        }
        catch(Exception){
            throw new Exception("Não foi possível inserir os dados no banco de dados");
        }
    }

}
?>
