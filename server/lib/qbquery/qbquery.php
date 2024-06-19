<?php

class qbquery{
    private $table;
    private $columns;
    private $distinct;
    private $groupBy;
    private $orderBy;
    private $caseWhen;
    private $where;
    private $whereArray;
    private $whereLike;
    private $query;
    private $innerJoin;
    private $leftJoin;

    public function __construct(string $table){
        (new DataBaseConnection());
        $this->table = $table;
    }
   
    public function insert(array $columns) {
        $tableColumns  = '';   
        $colummnsValue = '';

        $i = 1;
        foreach($columns as $column => $value) { 
            $value = gettype($value) == 'string' ? "'" .pg_escape_string(DataBaseConnection::$pgConnect, $value). "'" : $value;

            if($i == count($columns)) {
                $tableColumns .= "$column ";
                $colummnsValue .= "$value ";
            }
            else{
                $tableColumns .= "$column, ";
                $colummnsValue .= "$value, ";
            }

            $i++;
        }   

        $insert =  
        " INSERT INTO ".$this->table." (
           $tableColumns
        )
        VALUES 
        (
          $colummnsValue
        )
        RETURNING *
        ;
        ";
        
        $insert = preg_replace('/\n/', '', $insert);
        $insert = preg_replace('/\r/', '', $insert);

        try{
            return (new DataBaseConnection())->insert($insert);
        }catch(Exception) {
             return json_encode(AppError('Erro ao executar a inserção no banco de dados', 401));
        }
    }

    public function update($columns, array $conditions) {
        $columns = json_encode($columns);
        $columns = json_decode($columns, true);

        $update = "UPDATE $this->table";
        
        $i = 1;
        foreach($columns as $column => $value) {
            $value = gettype($value) == 'string' ? "'" .pg_escape_string(DataBaseConnection::$pgConnect, $value). "'" : $value;

            if($i == 1) {
                $update .= " SET $column = $value, ";
            }
            else{
                if($i == count($columns)) {
                    $update .= " $column = $value";
                }
                else{
                    $update .= " $column = $value, ";
                }
            }
            $i++;
        }
        $update .= $this->getConditions($conditions);
        try{
            (new DataBaseConnection())->update($update);

        }
        catch(Exception) {
             die;
        }
    }

    public function delete(string $condition) {
        $delete = "DELETE FROM $this->table WHERE $condition";
       
        try{
            (new DataBaseConnection())->delete($delete);
        }catch(Exception) {
             return json_encode(AppError('Erro ao executar a exclusão no banco de dados', 401));
        }
    }

    private function getConditions(array $conditions) {
        $condition = " WHERE ";
        $i = 1;
        foreach($conditions as $column => $value) {
            $value = gettype($value) == 'string' ? "'$value'" : $value;

            if($i == 1){
                $condition .= "$column = $value";
            }
            else {
                $condition .= " AND $column = $value ";
            }
         $i++;
        }

        return $condition;
    }

    public function setColumns($columns) {
        $this->columns = $columns;

        return $this;
    }

    public function distinct() {
        $this->distinct = true;

        return $this;
    }

    public function groupBy(array $groupBy) {
        $this->groupBy = $groupBy;

        return $this;
    }

    public function orderBy(array $orderBy){
        $this->orderBy = $orderBy;

        return $this;
    }

    public function caseWhen(string $column, array $values, string $newNameColumn, string $orElse = 'no value'){
        $this->caseWhen .= ", CASE $column ";
        foreach($values as $index => $value){
            $this->caseWhen .= " WHEN '$index' THEN '$value'";
        }
        $this->caseWhen .= " ELSE '$orElse' END AS $newNameColumn";

        return $this;
    }

    public function innerJoin(string $table, array $condition) {
        $this->innerJoin .= " INNER JOIN $table ON ";
        $i = 1;
        foreach($condition as $index => $value) {
            if(count($condition) > 1) {
                if($i == count($condition)) {
                    $this->innerJoin .= "$index = $value ";
                }
                else if($i != 1){
                    $this->innerJoin .= "AND $index = $value ";
                }
            }
            else{
               $this->innerJoin .= "$index = $value ";
            }
            $i++;
        }

        return $this;
    }
    public function leftJoin(string $table, array $condition) {
        $this->leftJoin .= " LEFT JOIN $table ON ";
        $i = 1;
        foreach($condition as $index => $value) {
            if(count($condition) > 1) {
                if($i == count($condition)) {
                    $this->leftJoin .= "$index = $value  ";
                }
                else if($i != 1){
                    $this->leftJoin .= "AND $index = $value ";
                }
            }
            else{
                $this->leftJoin .= "$index = $value ";
            }
            $i++;
        }

        return $this;
    }

    public function where(array $condition){
        $this->where = $condition;

        return $this;
    }

    public function whereArray(array $condition) {
        $this->whereArray = $condition;

        return $this;
    }

    public function whereLike(array $condition) {
        $this->whereLike = $condition;

        return $this;
    }

    public function getMany($quantity = null, $removeColumns = null) {
        $this->query = "SELECT ";
        $this->setQuery();
        if($quantity) {
            $this->query .= " LIMIT $quantity";
        }

        if($removeColumns) {
            $response = (new DataBaseConnection())->fetch_data($this->query);
            $newData  = []; 
            foreach($response as $data) {
                foreach($removeColumns as $column) {
                    unset($data[$column]);
                }
                array_push($newData, $data);
            }

            return $newData;
        }

        return (new DataBaseConnection())->fetch_data($this->query);
    }

    public function getFirst($removeColumns = null) {
        $this->query = "SELECT ";
        $this->setQuery();
        $this->setQueryLimit(1);
        $data = (new DataBaseConnection())->fetch_data($this->query);
        $json = json_encode($data);
        $data = json_decode($json);
        if($removeColumns) {
            foreach($removeColumns as $columns) {
                if($data) {
                    unset($data[0]->$columns);
                } 
            }
        }
        return $data ? $data[0] : null;
    }

    public function getSQL() {
        $this->setQuery();
        return $this->query;
    }

    private function setQuery() {
        $this->query = "SELECT ";

        $this->setQueryDistinct();
        $this->setQueryColumns();
        $this->setQueryCaseWhen();
        $this->setQueryTable();
        $this->setQueryInnerJoin();
        $this->setQueryLeftJoin();
        $this->setQueryWhere();
        $this->setQueryWhereArray();
        $this->setQueryWhereLike();
        $this->setQueryGroupBy();
        $this->setQueryOrderBy();
    }

    private function setQueryDistinct() {
        if($this->distinct) {
            $this->query .= "DISTINCT ";
        }
    }

    private function setQueryColumns() {
        $i = 1;

        if($this->columns){
             foreach($this->columns as $index => $value){
                if($value && $value != " "){
                    if($i == count($this->columns)){
                        $this->query .= " $index AS $value";
                    }
                    else {
                        $this->query .= " $index AS $value, ";
                    }
                }
                else{
                        $this->query .= " $index ";
                }
                $i++;
             }
        }
        else {
            $this->query .= " * ";
        }
    }

    private function setQueryCaseWhen() {
        if($this->caseWhen){
            $this->query .= " $this->caseWhen ";
        }
    }

    private function setQueryTable() {
        $this->query .= " FROM $this->table ";
    }

    private function setQueryInnerJoin() {
        if($this->innerJoin) {
            $this->query .= " $this->innerJoin ";
        }
    }

    private function setQueryLeftJoin() {
        if($this->leftJoin) {
            $this->query .= " $this->leftJoin ";
        }
    }
    
    
    private function setQueryWhere() {
        if($this->where) {
        $this->query .= " WHERE ";
        $i = 1;
        foreach($this->where as $column => $value) {
            $value = gettype($value) == 'string' ? "'$value'" : $value;

            if($i == 1){
                $this->query .= "$column = $value";
            }
            else {
                $this->query .= " AND $column = $value ";
            }
         $i++;
        }
        }

        return $this;
    }
    private function setQueryWhereArray() {
        if($this->whereArray) {
            $this->query .= " WHERE ";
            $i = 1;
            foreach($this->whereArray as $column => $value) {
                if($i == 1){
                    $this->query .= "$column @> ARRAY[$value]";
                }
                else {
                    $this->query .= " AND $column @> ARRAY[$value]";
                }
            $i++;
        }
        }

        return $this;
    }

    private function setQueryWhereLike() {
        if($this->whereLike) {
            if($this->where) {
                $this->query .= " AND ";
              }
              else {
                $this->query .= " WHERE ";
              }
               
               $i = 1;
              foreach($this->whereLike as $column => $value) {
                $dataTypeColumn = $this->getTypeColumnOfTable($column, $this->table)[0];

                if($dataTypeColumn['data_type'] == 'character varying') {
                    $value = strtolower($value);
                    $value = "'%$value%'";
                }


                if($dataTypeColumn['data_type'] == 'integer') {
                    $value = $value ? $value : 0;
                    if($i == 1) {
                        $this->query .= "$column = $value ";
                       }
                       else {
                           $this->query .= " AND $column = $value ";
                       }
                }
                else {
                    if($i == 1) {
                     $this->query .= "LOWER($column) LIKE $value";
                    }
                    else {
                        $this->query .= " AND LOWER($column) LIKE $value ";
                    }
                }
                
               $i++;
               
              }
        }

        return $this;
    }
    
    private function setQueryGroupBy() {
        if($this->groupBy) {
            $this->query .= " GROUP BY ";

            $i = 1;
            foreach($this->groupBy as $value){
                if($i == count($this->groupBy)){
                    $this->query .= " $value ";
                }
                else{
                    $this->query .= " $value, ";
                }

                $i++;
            }
        }
    }

    private function setQueryOrderBy() {
        if($this->orderBy) {
            $this->query .= " ORDER BY ";

            $i = 1;
            foreach($this->orderBy as $value){
                if($i == count($this->orderBy)){
                    $this->query .= " $value ";
                }
                else{
                    $this->query .= " $value, ";
                }

                $i++;
            }
        }
    }

    private function setQueryLimit(int $limit) {
        $this->query .= " LIMIT $limit";
    }

    public function getTypeColumnOfTable($column, $table) {
        $sql ="SELECT data_type
                FROM information_schema.columns
               WHERE table_name = '$table'
                 AND column_name = '$column'";
                 
        return (new DataBaseConnection())->fetch_data($sql);
    }
        
}