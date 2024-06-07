<?php

class CartService {
    public static function create($data) {
        response($data);    
    }

    public static function show($params) {
        return $params;
    }

    public static function update($id, $data) {
        response([
            $id, 
            $data
        ]);
    }

    public static function delete($id) {
        response($id);
    }
}