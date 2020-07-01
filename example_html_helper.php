<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
require_once 'vendor/autoload.php';


session_start();


use \Stnc\ShoppingCart\HtmlHelper;


$cart_name = 'stncCart'; // sepetin session değerine bir değer atadık
$cart= new HtmlHelper($cart_name);
$cart->groups=true;

//$help->hello(); // test


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




//  sepetteki ürün toplamı hakkında bilgi verir
 print_r( $cart->cartCount());

 //     * sepetteki ürün hakkında bilgiler verir 
 print_r( $cart->cartInfo());


 //html helper a ait olanlar 
 if (isset($_GET['table']) && $_GET['table']==true) {
 echo $cart->viewCartTableFull();
 }
 
 if (isset($_GET['json']) && $_GET['json']==true) {
    echo $cart->getJSON();
    }
    
    
    if (isset($_GET['miniCard']) && $_GET['miniCard']==true) {
    echo $cart->miniCart();
    }     
    
    if (isset($_GET['viewCartTablePrice']) && $_GET['viewCartTablePrice']==true) {
    echo $cart->viewCartTablePrice();
    }   

/*GetJson fonksiyonu

Sepeti json olarak geri dondürür ama json değerlerinde otomatik olarak ürünler tablo içinde oluşturulmuş olarak dönerler

http://cms.dev/sepet?action=ekle*/

//echo 


/*viewCartTablePrice fonksiyonu

sepetteki ler hakkında ürün adet ve tutar olarak bilgi verir, mini sepet dosyası içindir

http://cms.dev/sepet?action=mini_sepet_fiyat

//sonuc
/*
Toplam Ürün:	2 Ürün
Toplam Adet:	4 Adet
Toplam Tutar:	91,98 TL
*/


//echo $cart->viewCartTablePrice();




/*viewCartTableFull fonksiyonu

sepetteki ler hakkında ürün adet ve tutar olarak full liste bilgi verir.sepetim sayfası bunu kullanır

http://cms.dev/sepet?action=table

       sepet sayfası na basılıcak yerdir
      sepetteki ler hakkında table olarak ayrıntılı bilgi verir


*/

//echo $cart->viewCartTableFull();
/*
cartCount fonksiyonu

sepetteki ürün toplamı hakkında bilgi verir sepette kaç Adet ürün ve kaç ürün var

http://cms.dev/sepet?action=sepet_tutari


*/

//sepeti array donder 
echo '<pre>';
print_r($cart->viewCartArray());
echo '</pre>';