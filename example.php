<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
require_once 'vendor/autoload.php';

session_start();


use \Stnc\ShoppingCart\Cart;


$cart_name = 'stnc'; // sepetin session değerine bir değer atadık
$cart = new Cart($cart_name);
$cart->groups=false;


/* other call
$cart_other = new  \stnc\cart\Cart('stnc');
print_r($cart_other);
*/

//http://cart.test/example.php?add=100
if (isset($_GET['add']) && $_GET['add']==100) {
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
}

// sepete eklenenen her ürün için benzersiz bir id verilmesi gerekir
// 100 burada bunu temsil ediyor
// bu mesela şu olabilir urunler tablosundaki urun_id yada sku değeri olabilir
// bunlar tekil değerlerdir

//http://cart.test/example.php?add=110
if (isset($_GET['add']) && $_GET['add']==110) {
$data = array(
	'productID' => 100,
	'productName' => "cake",
	'productImageURL' => "https://example.org/cake.jpg",
	'URL' => "https://example.org/product/110",
	'price' => 80,
	"totalEach" => 1,
	'stockUnit'=>'unit',
	"totalPrice" => 80
);
$cart->addToCart("110", $data);
}
/*ürün silme
Sepetden ürün silmek
*/
//http://cart.test/example.php?remove=110
if (isset($_GET['remove']) && $_GET['remove']==110) {
	$cart->removeCart(110);
	}
	
	//sepeti tamamen boşaltır 
	//http://cart.test/example.php?empty=true
	if (isset($_GET['empty']) && $_GET['empty']==true) {
		$cart->emptyCart();
		}
	
	//sepeti array donder 
	  echo '<pre>';
	  print_r($cart->viewCartArray());
	  echo '</pre>';
	
	
	//  sepetteki ürün toplamı hakkında bilgi verir
	 print_r( $cart->cartCount());
	
	 //     * sepetteki ürün hakkında bilgiler verir 
	 print_r( $cart->cartInfo());
