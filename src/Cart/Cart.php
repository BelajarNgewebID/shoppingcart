<?php

namespace BNI;

class Cart {
    static $currentCart;
    static $currentItem;
    static $storedItem;
    static $toGet;

    public function response($res) {
        return json_encode($res);
    }
    public function grabCart() {
        return json_decode(file_get_contents('./Cart.json'), true);
    }
    public function writeContent($data) {
        $open = fopen('./Cart.json', 'w');
        fwrite($open, json_encode($data, true));
        fclose($open);
    }

    public function delete() {
        $toGet = self::$toGet;
        if($toGet == "item") {
            self::removeItem();
        }else {
            self::removeCart();
        }
    }
    public function removeItem() {
        $currentItem = self::$currentItem;
        $items = self::$storedItem;
        $i = 0;
        foreach($items as $item) {
            $iPP = $i++;
            if($item['id'] == $currentItem['id']) {
                unset($items[$iPP]);
            }
        }

        self::updateCart([
            'items' => $items
        ]);

        return $items;
    }
    public function removeCart() {
        $currentCart = self::$currentCart;
        
        $carts = $this->grabCart();
        $i = 0;
        foreach($carts as $cart) {
            $iPP = $i++;
            if($cart['id'] == $currentCart['id']) {
                unset($carts[$iPP]);
            }
        }

        $update = $this->writeContent($carts);

        return $carts;
    }
    public function where($query) {
        $carts = $this->grabCart();
        foreach($carts as $cart) {
            foreach($query as $key => $value) {
                if($cart[$key] == $query[$key]) {
                    self::$currentCart = $cart;
                }
            }
        }
        self::$toGet = "cart";

        return new Cart;
    }
    public function get() {
        if(self::$toGet == "item") {
            return self::$currentItem;
        }else if(self::$toGet == "cart") {
            return self::$currentCart;
        }else {
            // get all carts
            return $this->grabCart();
        }
    }
    public function createCart($attr) {
        error_reporting(1);

        $carts = $this->grabCart();
        $length = count($carts);

        $cartItems = [];
        if($attr['items'] != "") {
            $cartItems = $attr['items'];
        }
        
        $cart['id'] = $carts[$length - 1]['id'] + 1;
        $cart['name'] = $attr['name'];
        $cart['items'] = $cartItems;
        array_push($carts, $cart);

        $update = $this->writeContent($carts);

        return $carts;
    }
    public function addItem($item) {
        error_reporting(1);
        $currentCart = self::$currentCart;

        $itemAttr = [];
        if($item['attributes'] != "") {
            $itemAttr = $item['attributes'];
        }

        $cartItems = $currentCart['items'];
        $length = count($cartItems);
        $newItem['id'] = $cartItems[$length - 1]['id'] + 1;
        $newItem['title'] = $item['title'];
        $newItem['attributes'] = $itemAttr;
        array_push($cartItems, $newItem);

        $currentCart['items'] = $cartItems;

        self::updateCart($currentCart);

        return $currentCart;
    }
    public function add($item) {
        $toGet = self::$toGet;
        if($toGet == "item") {
            self::addItem($item);
        }else {
            self::createCart($item);
        }
    }
    public function item($query = NULL) {
        $currentCart = self::$currentCart;

        $items = $currentCart['items'];
        self::$storedItem = $items;
        if($query != NULL) {
            foreach($items as $item) {
                foreach($query as $key => $value) {
                    if($item[$key] == $query[$key]) {
                        self::$currentItem = $item;
                    }
                }
            }
        }else {
            self::$currentItem = $items;
        }

        self::$toGet = "item";

        return new Cart;
    }
    public function update($toUpdate) {
        $toGet = self::$toGet;
        if($toGet == "item") {
            self::updateItem($toUpdate);
        }else {
            self::updateCart($toUpdate);
        }
    }
    public function updateItem($toUpdate) {
        $currentItem = self::$currentItem;

        foreach($toUpdate as $key => $value) {
            $currentItem[$key] = $value;
        }

        $items = self::$storedItem;
        $i = 0;
        foreach($items as $item) {
            $iPP = $i++;
            if($item['id'] == $currentItem['id']) {
                $items[$iPP] = $currentItem;
            }
        }

        self::updateCart([
            'items' => $items
        ]);

        return $items;
    }
    public function updateCart($toUpdate) {
        $currentCart = self::$currentCart;

        $cartId = $currentCart['id'];
        foreach($toUpdate as $key => $value) {
            $currentCart[$key] = $value;
        }

        $carts = $this->grabCart();
        $i = 0;
        foreach($carts as $cart) {
            $iPP = $i++;
            if($cart['id'] == $currentCart['id']) {
                $carts[$iPP] = $currentCart;
            }
        }
        
        $update = $this->writeContent($carts);
        return $carts;
    }
}