Alýþveriþ sepeti sistemi

Alýþveriþ sepeti sistemi Bu sýnýf kullanýcýlar siteyi ziyaret ederken ürünlerin eklenebileceði, 
"session"da saklanan bir alýþveriþ sepeti oluþturmamýz için bize yardým eder.
Basit esnek ve kolay uygulabilir geliþmiþ bir sýnýfdýr.
Alýþveriþ sepetindeki ürünlerin silinmesi, miktarýnýn deðiþtirlmesi veya yeni ürün eklenmesi gibi iþlemlere olanak saðlar.

Sepet fonsiyonunu tanýtmak


        // eðer use olarak kullanýlacaksa
        // use \Lib\Cart;
        // $sepet = new cart($cart_name, PUBLIC_PATH);
        $cart_name = 'stnc'; // sepetin session deðerine bir deðer atadýk
        
        $cart = new \Lib\Cart('stnc', PUBLIC_PATH);

ADDTOCART fonksiyonu (Sepete ürün ekleme)

Sepete ürün ekleme için kullanýlýr ayný id'li üründen tekrar eklenirse kontrol eder ve sadece ürünün fiyatýný ve adetini günceller

http://cms.dev/sepet?action=ekle

   $cart = new \Lib\Cart('stnc', PUBLIC_PATH);
            $data = array(
                'UrunID' => 02,
                'UrunAdi' => "çikolata  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "biskuvi.jpg",
                'URL' => "biskuvi.jpg",
                'Fiyat' => 40.99,
                "ToplamAdet" => 1,
                "ToplamFiyat" => ""
            );
            // sepete eklenenen her ürün için benzersiz bir id verilmesi gerekir
            // 34 burada bunu temsil ediyor
            // bu mesela þu olabilir urunler tablosundaki urun_id yada sku deðeri olabilir
            // bunlar tekil deðerlerdir
            $cart->addToCart("100", $data);
            
            $data = array(
                'UrunID' => 05,
                'UrunAdi' => "kraker  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "biskuvi.jpg",
                'URL' => "biskuvi.jpg",
                'Fiyat' => 5,
                "ToplamAdet" => 1,
                "ToplamFiyat" => ""
            );
            $cart->addToCart("125", $data);

removeCart fonksiyonu (Sepetden ürün silmek )

Sepetden ürün silmek

http://cms.dev/sepet?action=sil

   $cart = new \Lib\Cart('stnc', PUBLIC_PATH);
                       $data = array(
                'UrunID' => 02,
                'UrunAdi' => "çikolata  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "biskuvi.jpg",
                'URL' => "biskuvi.jpg",
                'Fiyat' => 40.99,
                "ToplamAdet" => 1,
                "ToplamFiyat" => ""
            );

            $cart->addToCart("100", $data);
            $cart->viewCart();
     		$cart->removeCart(100);
            $cart->viewCart();

viewCart fonksiyonu

Sepeti array olarak verir

http://cms.dev/sepet?action=ekle

   $cart = new \Lib\Cart('stnc', PUBLIC_PATH);
            $data = array(
                'UrunID' => 02,
                'UrunAdi' => "çikolata  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "biskuvi.jpg",
                'URL' => "biskuvi.jpg",
                'Fiyat' => 40.99,
                "ToplamAdet" => 1,
                "ToplamFiyat" => ""
            );
       $cart->addToCart("125", $data);
            
           //sepet blgisini ver
           $cart-> viewCart();

GetJson fonksiyonu

Sepeti json olarak geri dondürür ama json deðerlerinde otomatik olarak ürünler tablo içinde oluþturulmuþ olarak dönerler

http://cms.dev/sepet?action=ekle

   $cart = new \Lib\Cart('stnc', PUBLIC_PATH);
            $data = array(
                'UrunID' => 02,
                'UrunAdi' => "çikolata  ",
                'Resim' => "biskuvi.jpg",
                'ResimURL' => "biskuvi.jpg",
                'URL' => "biskuvi.jpg",
                'Fiyat' => 40.99,
                "ToplamAdet" => 1,
                "ToplamFiyat" => ""
            );
       	$cart->addToCart("125", $data);
        $cart->viewCart();
        echo $cart->getJSON();
emptyCart fonksiyonu

sepeti boþaltmak için kullanýlýr

http://cms.dev/sepet?action=bosalt

   $cart = new \Lib\Cart('stnc', PUBLIC_PATH);
   $cart->emptyCart();
  $cart->viewCart();
viewCartTablePrice fonksiyonu

sepetteki ler hakkýnda ürün adet ve tutar olarak bilgi verir, mini sepet dosyasý içindir

http://cms.dev/sepet?action=mini_sepet_fiyat

   $cart = new \Lib\Cart('stnc', PUBLIC_PATH);
   $cart->viewCartTablePrice();

//sonuc 
/*
Toplam Ürün:	2 Ürün
Toplam Adet:	4 Adet
Toplam Tutar:	91,98 TL
*/
viewCartTableFull fonksiyonu

sepetteki ler hakkýnda ürün adet ve tutar olarak full liste bilgi verir.sepetim sayfasý bunu kullanýr

http://cms.dev/sepet?action=table

   $cart = new \Lib\Cart('stnc', PUBLIC_PATH);
   
     /*  sepet sayfasý na basýlýcak yerdir
      * sepetteki ler hakkýnda table olarak ayrýntýlý bilgi verir
      */
  
 echo $cart->viewCartTableFull();

cartCount fonksiyonu

sepetteki ürün toplamý hakkýnda bilgi verir sepette kaç Adet ürün ve kaç ürün var

http://cms.dev/sepet?action=sepet_tutari

$cart = new \Lib\Cart('stnc', PUBLIC_PATH);
print_r( $cart->cartCount());
//çýktýsý             
/*
Array
(
    [toplam_urun] => 2
    [toplam_adet] => 4
)
*/