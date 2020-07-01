<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
require_once 'vendor/autoload.php';
/*use \DB\MYSQL as dbs;
$db = new dbs\Mysql();*/
  define('SESSION_PREFIX', 'selman');
define('BISLEM_RESIM_BULUNAMADİ','');
session_start();


//use \Stnc\ShoppingCart\Cart;
use \Stnc\ShoppingCart\HtmlHelper;

$help= new HtmlHelper("sdsd","sd");
$help->hello();
die();
$cart_name = 'stnc'; // sepetin session değerine bir değer atadık
$cart = new Cart($cart_name, 's');

/* other call
$cart_other = new  \stnc\cart\Cart('stnc', 'ds');
print_r($cart_other);
*/
$data = array(
		'UrunID' => 02,
		'UrunAdi' => "çikolata  ",
		'Resim' => "biskuvi.jpg",
		'ResimURL' => "biskuvi.jpg",
		'URL' => "biskuvi.jpg",
		'price' => 40.99,
		"totalEach" => 1,
		'StokBirimi'=>'adet',
		"totalPrice" => ""
);
$cart->addToCart("100", $data);

$data = array(
		'UrunID' => 02,
		'UrunAdi' => "çikolata  ",
		'Resim' => "biskuvi.jpg",
		'ResimURL' => "biskuvi.jpg",
		'URL' => "biskuvi.jpg",
    'price' => 40.99,
    "totalEach" => 1,
    'StokBirimi'=>'adet',
    "totalPrice" => ""
);
$cart->addToCart("110", $data);
// sepete eklenenen her ürün için benzersiz bir id verilmesi gerekir
// 100 burada bunu temsil ediyor
// bu mesela şu olabilir urunler tablosundaki urun_id yada sku değeri olabilir
// bunlar tekil değerlerdir


$data = array(
		'UrunID' => 05,
		'UrunAdi' => "kraker  ",
		'Resim' => "biskuvi.jpg",
		'ResimURL' => "biskuvi.jpg",
		'URL' => "biskuvi.jpg",
    'price' => 40.99,
    "totalEach" => 1,
    'StokBirimi'=>'adet',
    "totalPrice" => ""
);
$cart->addToCart("125", $data);
echo '<pre>';

/*ürün silme
Sepetden ürün silmek

http://cms.dev/sepet?action=sil
*/

$cart->removeCart(100);
/*viewCart fonksiyonu

Sepeti array olarak verir

http://cms.dev/sepet?action=ekle*/
print_r( $cart->viewCart());


/*GetJson fonksiyonu

Sepeti json olarak geri dondürür ama json değerlerinde otomatik olarak ürünler tablo içinde oluşturulmuş olarak dönerler

http://cms.dev/sepet?action=ekle*/

//echo $cart->getJSON();


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

print_r($cart);



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
print_r( $cart->cartCount());
die;
/*emptyCart fonksiyonu

sepeti boşaltmak için kullanılır

http://cms.dev/sepet?action=bosalt*/
$cart->emptyCart();
print_r( $cart->viewCart());
