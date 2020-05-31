

<hr>

<strong> SCREENSHOT </strong>

<img  src="https://raw.githubusercontent.com/stnc/golang-echo-gorm-pagination-BLOG/crud/scrshot.png">

# Alışveriş sepeti sistemi

Alışveriş sepeti sistemi Bu sınıf kullanıcılar siteyi ziyaret ederken ürünlerin eklenebileceği, <br>
"session"da saklanan bir alışveriş sepeti oluşturmamız için bize yardım eder.<br>
Basit esnek ve kolay uygulabilir gelişmiş bir sınıfdır.<br>
Alışveriş sepetindeki ürünlerin silinmesi, miktarının değiştirlmesi veya yeni ürün eklenmesi gibi işlemlere olanak sağlar.

## Sepet fonsiyonunu tanıtmak
```php
  define('SESSION_PREFIX', 'stnccart_');
define('BISLEM_RESIM_BULUNAMADİ','');
session_start();
        // eğer use olarak kullanılacaksa
        // use use \stnc\shoppingCart\Cart;
        // $sepet = new cart($cart_name, PUBLIC_PATH);
    use \stnc\shoppingCart\Cart;
    $cart_name = 'stnc'; // sepetin session değerine bir değer atadık
    $cart = new cart($cart_name, 's');
```
## ADDTOCART fonksiyonu (Sepete ürün ekleme)

Sepete ürün ekleme için kullanılır aynı id'li üründen tekrar eklenirse kontrol eder ve sadece ürünün fiyatını ve adetini günceller

http://cms.dev/sepet?action=ekle
```php
   $cart = new \stnc\shoppingCart\Cart('stnc', PUBLIC_PATH);
            $data = array(
                'UrunID' => 02,
                'UrunAdi' => "çikolata  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "",
                'URL' => "biskuvi.jpg",
                'Fiyat' => 40.99,
                "totalEach" => 1, //stok adeti
                "totalPrice" => ""
            );
            // sepete eklenenen her ürün için benzersiz bir id verilmesi gerekir
            // 34 burada bunu temsil ediyor
            // bu mesela şu olabilir urunler tablosundaki urun_id yada sku değeri olabilir
            // bunlar tekil değerlerdir
            $cart->addToCart("100", $data);
            
            $data = array(
                'UrunID' => 05,
                'UrunAdi' => "kraker  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "biskuvi.jpg",
                'URL' => "biskuvi.jpg",
                'Fiyat' => 5,
                "totalEach" => 1,
                "totalPrice" => ""
            );
            $cart->addToCart("125", $data);
```
## removeCart fonksiyonu (Sepetden ürün silmek )

Sepetden ürün silmek içindir 

http://cms.dev/sepet?action=sil
```php
   $cart = new \stnc\shoppingCart\Cart('stnc', PUBLIC_PATH);
                       $data = array(
                'UrunID' => 02,
                'UrunAdi' => "çikolata  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "biskuvi.jpg",
                'URL' => "biskuvi.jpg",
                'price' => 40.99,
                "totalEach" => 1,
                "totalPrice" => ""
            );

            $cart->addToCart("100", $data);
            $cart->viewCart();
     		$cart->removeCart(100);
            $cart->viewCart();
```
## viewCart fonksiyonu

Sepeti array olarak verir

http://cms.dev/sepet?action=ekle
```php
   $cart = new \stnc\shoppingCart\Cart ('stnc', PUBLIC_PATH);
            $data = array(
                'UrunID' => 02,
                'UrunAdi' => "çikolata  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "biskuvi.jpg",
                'URL' => "biskuvi.jpg",
                'Fiyat' => 40.99,
                "totalEach" => 1,
                "totalPrice" => ""
            );
       $cart->addToCart("125", $data);
            
           //sepet blgisini ver
           $cart-> viewCart();
```
## GetJson fonksiyonu

Sepeti json olarak geri dondürür ama json değerlerinde otomatik olarak ürünler tablo içinde oluşturulmuş olarak dönerler

http://cms.dev/sepet?action=ekle
```php
   $cart = new \stnc\shoppingCart\Cart('stnc', PUBLIC_PATH);
            $data = array(
                'UrunID' => 02,
                'UrunAdi' => "çikolata  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "biskuvi.jpg",
                'URL' => "biskuvi.jpg",
                'Fiyat' => 40.99,
                "totalEach" => 1,
                "totalPrice" => ""
            );
       	$cart->addToCart("125", $data);
        $cart->viewCart();
        echo $cart->getJSON();
```		
## emptyCart fonksiyonu

sepeti boşaltmak için kullanılır

http://cms.dev/sepet?action=bosalt
```php
   $cart = new \stnc\shoppingCart\Cart('stnc', PUBLIC_PATH);
   $cart->emptyCart();
  $cart->viewCart();
```
## viewCartTablePrice fonksiyonu

sepetteki ler hakkında ürün adet ve tutar olarak bilgi verir, mini sepet dosyası içindir

http://cms.dev/sepet?action=mini_sepet_fiyat
```php
   $cart = new \stnc\shoppingCart\Cart('stnc', PUBLIC_PATH);
   $cart->viewCartTablePrice();

//sonuc 
/*
Toplam Ürün:	2 Ürün
Toplam Adet:	4 Adet
Toplam Tutar:	91,98 TL
*/
```
##viewCartTableFull fonksiyonu

sepetteki ler hakkında ürün adet ve tutar olarak full liste bilgi verir.sepetim sayfası bunu kullanır

http://cms.dev/sepet?action=table
```php
   $cart = new \stnc\shoppingCart\Cart('stnc', PUBLIC_PATH);
   
     /*  sepet sayfası na basılıcak yerdir
      * sepetteki ler hakkında table olarak ayrıntılı bilgi verir
      */
  
 echo $cart->viewCartTableFull();
```
## cartCount fonksiyonu

sepetteki ürün toplamı hakkında bilgi verir sepette kaç Adet ürün ve kaç ürün var

http://cms.dev/sepet?action=sepet_tutari
```php
$cart = new \stnc\shoppingCart\Cart('stnc', PUBLIC_PATH);
print_r( $cart->cartCount());
//çıktısı             
/*
Array
(
    [toplam_urun] => 2
    [toplam_adet] => 4
)
*/
```
demo page thx 
https://startbootstrap.com/templates/heroic-features/
