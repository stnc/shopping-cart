

<hr>

<strong> SCREENSHOT </strong>

<img  src="https://raw.githubusercontent.com/stnc/shopping-cart/master/screen.png">


# Yükleme /Install  

composer require stnc/shopping-cart


# Alışveriş sepeti sistemi

Alışveriş sepeti sistemi Bu sınıf kullanıcılar siteyi ziyaret ederken ürünlerin eklenebileceği, <br>
"session"da saklanan bir alışveriş sepeti oluşturmamız için bize yardım eder.<br>
Basit esnek ve kolay uygulabilir gelişmiş bir sınıfdır.<br>
Alışveriş sepetindeki ürünlerin silinmesi, miktarının değiştirlmesi veya yeni ürün eklenmesi gibi işlemlere olanak sağlar.

## Sepet fonsiyonunu tanıtmak
```php

        // eğer use olarak kullanılacaksa
        // use use \Stnc\ShoppingCart\Cart;
$cart_name = 'stnc'; // sepetin session değerine bir değer atadık
$cart = new Cart($cart_name);
$cart->groups=false;
```
## ADDTOCART fonksiyonu (Sepete ürün ekleme)

Sepete ürün ekleme için kullanılır aynı id'li üründen tekrar eklenirse kontrol eder ve sadece ürünün fiyatını ve adetini günceller

http://cms.dev/sepet?action=ekle
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
	  print_r($cart->viewCartArray());
	  echo '</pre>';
```
## removeCart fonksiyonu (Sepetden ürün silmek )

Sepetden ürün silmek içindir 


```php
   $cart->removeCart(100);
	  echo '<pre>';
	  print_r($cart->viewCartArray());
	  echo '</pre>';
```
## viewCart fonksiyonu


## emptyCart fonksiyonu

sepeti boşaltmak için kullanılır
```php
   $cart->emptyCart();
 	  echo '<pre>';
	  print_r($cart->viewCartArray());
	  echo '</pre>';
```

## viewCartArray fonksiyonu

sepeti array olarak gösterir 
```php
	  echo '<pre>';
	  print_r($cart->viewCartArray());
	  echo '</pre>';
```

	// 
	 print_r( $cart->cartCount());

## cartCount fonksiyonu

 sepetteki ürün toplamı hakkında bilgi verir
```php
	  print_r($cart->cartCount());
```

## cartInfo fonksiyonu

sepetteki ürün hakkında bilgiler verir 
```php
	  print_r($cart->cartInfo());
```



```
demo page thx 
https://startbootstrap.com/templates/heroic-features/
