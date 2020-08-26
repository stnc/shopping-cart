
# PHP SHOPPING CART

php simple shopping cart class and html ajax  example 

#  Install  
```php
composer require stnc/shopping-cart
```
<hr>

<strong> SCREENSHOT </strong>

<img  src="https://raw.githubusercontent.com/stnc/shopping-cart/master/screen2.png">

<img  src="https://raw.githubusercontent.com/stnc/shopping-cart/master/screen.png">


<hr>




## init
```php

       
        // use use \Stnc\ShoppingCart\Cart;
$cart_name = 'stnc'; // sepetin session değerine bir değer atadık
$cart = new Cart($cart_name);
$cart->groups=false;
```
## ADDTOCART function

add to cart

```php
$data = array(
		'productID' => 100,
		'productName' => "ice cream",
		'productImageURL' => "https://example.org/icecream.jpg",
		'URL' => "https://example.org/product/100",
		'price' => 40.99,
		"totalEach" => 1,
		'stockUnit'=>'unit',
		"totalPrice" => 40.99
);
$cart->addToCart("100", $data);
	  echo '<pre>';
	  print_r($cart->getArray());
	  echo '</pre>';
```
## removeCart function 

cart to remove 


```php
   $cart->removeCart(100);
	  echo '<pre>';
	  print_r($cart->getArray());
	  echo '</pre>';
```

## getJson function 

cart json info 


```php
   $cart->getJson();
```


## viewCart function


## emptyCart function

cart empty
```php
   $cart->emptyCart();
 	  echo '<pre>';
	  print_r($cart->getArray());
	  echo '</pre>';
```

## getArray function

cart to result array 
```php
	  echo '<pre>';
	  print_r($cart->getArray());
	  echo '</pre>';
```

	// 
	 print_r( $cart->cartCount());

## cartCount fonksiyonu

gives information about the total of items in the basket

```php
	  print_r($cart->cartCount());
```

## cartInfo fonksiyonu

Cart information 
```php
	  print_r($cart->cartInfo());
```



```
demo page thx 
https://startbootstrap.com/templates/heroic-features/
