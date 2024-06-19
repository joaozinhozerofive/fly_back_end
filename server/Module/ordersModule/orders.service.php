<?php
require_once(dirname(__DIR__) . '/productsModule/products.service.php');
class OrdersService{

    public static function create($data){
        $products    = $data->products;
        $adress_id   = $data->adress_id;
        $user_id     = getUserId();
        
        self::validateOrderCreate($data);

        $products   = self::getProductsWithPrice($products); 
        $total_price = self::getTotalPriceByArrayProducts($products);
       
        try {   
            $order = (new qbquery('orders'))
            ->insert([
                "status"      => 1, 
                "user_id"     => $user_id,
                "adress_id"   => $adress_id,
                "total_price" => $total_price,
                "created_at"  => date('Y-m-d H:i:s')
            ]);

            foreach($products as $product) {
                self::insertOrderProducts($order['id'], $product);
            }

            AppSucess("Pedido feito com sucesso", 201);
        }
        catch (Exception $e) {
            AppError("Não foi possível adicionar o pedido.", 400);
        }
    }

    public static function show($params) {
        $oders =  self::getOrdersByRouteParams($params);

        return $oders;
    }

    public static function update($id, $data) {
        $order = getData('orders', ['id' => $id]);

        if(!$order) {
            AppError("Pedido não encontrado", 404);
        }

        $order    = self::getDataOrderUpdate($order, $data);
        $products = $order ->products;
        unset($order->products);

        try {
            (new qbquery('orders'))
            ->update($order, ['id' => $id]);

            self::deleteOrderProductsByOrderId($order->id);

            foreach($products as $product) {
                self::insertOrderProducts($order->id, $product);  
            }

            AppSucess("Pedido atualizado com sucesso!");
        }
        catch(Exception $e) {
            AppError('Não foi possível atualizar pedido.', 400);
        }
    }

    public static function validateOrderCreate($data) {
        $products   = $data->products;
        $adress_id = $data->adress_id;

        if(!trim($adress_id)) {
            AppError('É necessário informar um endereço.', 401);
        }

        if(!$products) {
            AppError('É necessário informar ao menos um produto.', 401);
        }

        foreach($products as $product) {
            $savedProduct = getData('products', ['product_id' => $product->id]);

            if(!$savedProduct) {
                AppError("O produto (id:$product->id) não existe.", 401);
            }

            if($product->quantity > 10) {
                AppError('Não é possível comprar mais de 10 unidades de um produto em uma só vez.', 401);
            }
        }
    } 
    
    public static function getProductsWithPrice($products) {
        $newProduct = [];
        foreach($products as $product) {
            $savedProduct  = getData('products', ['product_id' => $product->id]);
            $product_price = floatval(str_replace(",", ".", $savedProduct->product_price));
            $totalPrice    = $product_price * $product->quantity;
            $product->total_price = $totalPrice; 
            array_push($newProduct, $product);
        }

        return $newProduct;
    }


    public static function getTotalPriceByArrayProducts($products) {
        $price = 0;

        foreach($products as $product) {
            $price += $product->total_price;
        }

        return getPriceFormattedPtBr($price);
    }

    public static function getOrdersByRouteParams($params) {
        $id      = intval(pr_value($params, 'id'));
        $user_id = intval(pr_value($params, 'user_id'));

        if($id) {
            $order = self::getOrderById($id);

            if(!$order) {
                AppError('Pedido não encontrado.', 404);
            }

            return $order;
        }

        if(!empty($user_id)) {
            $order = self::getOrderByUserId($user_id);

            if(!$order) {
                AppError('Pedido não encontrado.', 404);
            }

            return $order;
        }

        if($params) {
            return self::getOrderByLikeParams($params); 
        }

        return self::getManyOrders();
    }

    public static function getOrderById($id) {
        $order =  (new qbquery('orders'))
        ->where([
            'id' => $id
        ])
        ->caseWhen('status', [
            1 => 'Pendente', 
            2 => 'Em rota de entrega',
            3 => 'Entregue',
            4 => 'Cancelado/Devolvido',
        ], 'descriptionStatus')
        ->getFirst();

        if($order) {
            $products = self::getOrderProductsByOrderId($order->id);
            $order->products = $products;
        }

        return $order;
    }
    public static function getOrderByUserId($userId) {
        $order =  (new qbquery('orders'))
        ->where([
            'user_id' => $userId
        ])
        ->caseWhen('status', [
            1 => 'Pendente', 
            2 => 'Em rota de entrega',
            3 => 'Entregue',
            4 => 'Cancelado/Devolvido',
        ], 'descriptionStatus')
        ->getFirst();

        if($order) {
            $products = self::getOrderProductsByOrderId($order->id);
            $order->products = $products;
        }

        return $order;
    }

    public static function getOrderProductsByOrderId($orderId) {
        return (new qbquery('order_products'))
        ->where([
            'order_id' => $orderId
        ])
        ->getMany();
    }

    public static function getOrderByLikeParams($params) {
        $params = objectToArrayAssoc($params);

        $orders = (new qbquery('orders'))
        ->whereLike($params)
        ->caseWhen('status', [
            1 => 'Pendente', 
            2 => 'Em rota de entrega',
            3 => 'Entregue',
            4 => 'Cancelado/Devolvido',
        ], 'descriptionStatus')
        ->getMany();
        
        $newOrder = [];

        foreach($orders as $order) {
            $products = self::getOrderProductsByOrderId($order['id']);
            $order['products'] = $products;
            array_push($newOrder, $order);
        }

        return $newOrder;
    }

    public static function getManyOrders() {
        $orders = (new qbquery('orders'))
        ->caseWhen('status', [
            1 => 'Pendente', 
            2 => 'Em rota de entrega',
            3 => 'Entregue',
            4 => 'Cancelado/Devolvido',
        ], 'descriptionStatus')
        ->getMany();

        $newOrder = [];

        foreach($orders as $order) {
            $products = self::getOrderProductsByOrderId($order['id']);
            $order['products'] = $products;
            array_push($newOrder, $order);
        }

        return $newOrder;
    }

    public static function getDataOrderUpdate($order, $data) {
        $status     = $data->status;
        $adress_id  = $data->adress_id;
        $products   = self::getProductsWithPrice($data->products);
        $totalPrice = self::getTotalPriceByArrayProducts($products);
        
        $order->adress_id   = trim($adress_id) ? $adress_id : $order->adress_id;
        $order->status      = trim($status)    ? $status    : $order->status;
        $order->products    = $products;
        $order->total_price = $totalPrice;  
        $order->updated_at  = date('Y-m-d H:i:s');
        
        return $order;
    }

    public static function deleteOrderProductsByOrderId($orderId) {
        return (new qbquery('order_products'))
        ->delete("order_id = $orderId");
    }

    public static function insertOrderProducts($orderId, $product) {
        $price = getPriceFormattedPtBr($product->total_price);
        
        (new qbquery('order_products'))
         ->insert([
            'order_id'      => $orderId, 
            'product_id'    => $product->id,
            'quantity'      => $product->quantity,
            'price'         => $price
        ]);
    }
}