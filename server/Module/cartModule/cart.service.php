<?php

class CartService {
    public static function create($data) {
        responseJson($data);    
    }

    public static function show($params) {
        return $params;
    }

    public static function update($id, $data) {
        responseJson([
            $id, 
            $data
        ]);
    }

    public static function delete($id) {
        responseJson($id);
    }
}