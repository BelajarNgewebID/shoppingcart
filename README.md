# Shopping Cart Helper
No worries about your online shop. Just use this to store temporary shopping cart data

## Installation
---
```
composer require bni/shoppingcart
```

## Cart Usage
---
### Base Structure
```
[{
    "id": 1,
    "name": "Cart name",
    "items": []
}]
```
### Get All Carts
```
$cart = Cart::get();
```
### Get Cart with condition
```
$cart = Cart::where(['id' => 1])->get();
// or
$cart = Cart::where(['name' => 'My cart'])->get();
```
### Create New Cart
```
$cart = Cart::add([
    'name' => 'Shopping Cart Name'
]);
```
### Edit Cart
```
$cart = Cart::where(['id' => 1])->update([
    'name' => 'New Cart name'
]);
```

### Delete cart
```
$cart = Cart::where(['id' => 1])->delete();
```


## Items usage
---
### Base Structure
```
[{
    ...
    "items": [
        {
            "id": 1,
            "title": "Product name",
            "attributes": {
                "weight": 250
            }
        }
    ]
}]
```
*You can add your own attributes*

### Get all items
```
$item = Cart::where(['id' => 1])->item()->get();
```

### Get current item
```
$item = Cart::where(['id' => 1])->item(['id' => 2])->get();
```
*item() parameter like where(), you can use id or name*

### Add new item
```
$item = Cart::where(['id' => 1])->item()->add([
    'title' => 'Your product item',
    'attributes' => [
        'weight' => 250
    ]
]);
```

### Delete item
```
$item = Cart::where(['id' => 1])->item(['id' => 2])->delete();
```

### Edit item
```
$item = Cart::where(['id' => 1])->item(['id' => 2])->update([
    'title' => 'Edited product'
]);
```