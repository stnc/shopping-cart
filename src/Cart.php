<?php

/**
 * @author selman.tunc
 *
 */
namespace stnc\cart;

/**
 * fiyat hesaplamaları ve o türden özellikdeki bilgileri barındıracak
 * Copyright (c) 2015
 *
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>
 *
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 
 * @link http://github.com/stnc
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class Cart
{


    public $public_path;
    public $productWareHouseControl;
    /**
     * cookie aktif olacak mı
     *
     * @var $cookie_enabled
     */
    public $cookie_enabled = true;

    /**
     * cookie nin tarihi
     *
     * @var $Cookie_Date
     */
    public $Cookie_Date = 86400;
    // 1 Gün --- one date
    
    /**
     * burası session a eklenecek tüm array verisini tutar
     *
     * @var $session
     */
    protected $session = array();

    /**
     * gecerli session anahtarı
     *
     * @var string $SessionName
     */
    protected $SessionName = null;

    /**
     * son eklenen ürün id değeri
     *
     * @var int $lastAdded
     */
    public $lastAdded = null;

    /**
     * genel parasal toplamlar yapılır
     *
     * @var float $SubTotal
     */
    public $SubTotal = null;

    /**
     * resim klasoruleri için varsayaılan yolu set eder
     *
     * @var string $PUBLIC_PATH
     */
    private $PublicPath = null;

    /**
     * kurucu ayarlar
     * session name set edeilir
     *
     * @param string $value
     * @param string $PublicPath
     *            puplic klasoru
     */
    function __construct($SessionName, $PublicPath)
    {
        $this->SessionName = SESSION_PREFIX . $SessionName;
        $this->public_path = $PublicPath;
        $this->getSessionCart();
    }
    
    // destruct - unset cart var
    public function __destruct()
    {
        unset($this->session);
    }
    
    // sihirli ayarlar
    public function __set($name, $value)
    {
        switch ($name) {
            case 'discount': // indirim
            case 'bonusProduct': // hediye urun falan
                                 
                // Burada daha yeni şeyler ekleyebilir
                                 // son eklenen ürünler içindir
                $this->session[$this->lastAdded][$name] = $value;
                $this->setSessionCart();
                break;
        }
    }

    /**
     * sepetten ürünü çıkartır
     *
     * @param int $id
     *
     */
    public function removeCart($id)
    {
        $_SESSION[SESSION_PREFIX . $this->productWareHouseControl] = false;
        if (count($this->session) > 0) {
            if (array_key_exists($id, $this->session)) {
                unset($this->session[$id]);
                // unset ($this->session[$id]['ToplamAdet']);
                // unset ($this->session[$id]['Fiyat']);
            }
        }
        return $this->setSessionCart();
    }

    /**
     * sepetim sayfasından gelen isteğe göre ,sepetten ürünü çıkartır
     *
     * @param int $id
     *
     */
    public function AJAXremoveCart($id, $adet = 1)
    {
        $this->lastAdded = $id;
        if (count($this->session) > 0) {
            if (array_key_exists($id, $this->session)) {
                $this->session[$id]['ToplamAdet'] -= $adet;
                /* $this->session[$id]['ToplamAdet'] -= 1; */
                $this->session[$id]['ToplamFiyat'] = ($this->session[$id]['Fiyat'] * $this->session[$id]['ToplamAdet']);
            }
        }
        return $this->setSessionCart();
    }

    function addToCart($id, $data, $dataType = 'noajax')
    {
        /*
         * echo '<pre>';
         * print_r($this->session[$id]);
         * echo '</pre>';
         */
        $this->lastAdded = $id;
        // urun zaten eklenmişse ve tekrar gelirse ToplamAdetini artır
        if (array_key_exists($id, $this->session)) {
            $total = $data["ToplamAdet"];
            // $this->session[$id]['ToplamAdet'] = $this->v[$id]['ToplamAdet'] + $this->session[$id]['ToplamAdet'];
            $this->session[$id]['ToplamAdet'] += $total;
            $this->session[$id]['ToplamFiyat'] = ($this->session[$id]['Fiyat'] * $this->session[$id]['ToplamAdet']);
        } else { /* yeni urunse ekle direk ekle */
            $this->session[$id] = $data;
            //Bir dizinin başlangıcına bir veya daha fazla eleman ekler fakat dizi keyleri onemli o yuzden bekleyecek
            // array_unshift( $this->session[$id],$data);
        }

        // bu kısım alt tarafı //$_SESSION[$this->SessionName][$id]['ToplamAdet'] += $_SESSION[$this->SessionName][$id]['ToplamAdet'] + $total; echo $_SESSION[$this->SessionName][$id]['ToplamAdet'];
        return $this->setSessionCart();
    }

    
    

    
    /**
     * sepetin Fiyatlarını hesapla
     *
     * @return float
     */
    public function updateSubTotal()
    {
        $GenelToplamFiyat=0;
        if (sizeof($this->session) > 0) {
            foreach ($this->session as $id => $item) {
                // silme // $this->session[$id]['ToplamAdet'];$this->session[$id]['ToplamFiyat']; echo ($item['ToplamFiyat']);
                $GenelToplamFiyat = $GenelToplamFiyat + $item['ToplamFiyat'];
                // $GenelToplamFiyat += $item['ToplamFiyat'] ;//silme
                $this->SubTotal = $GenelToplamFiyat;
            }
            return ($GenelToplamFiyat);
        } else {
            return 0;
        }
    }

    /**
     * şepeti boşalt
     *
     * @return mixed
     */
    public function emptyCart()
    {
        // unset($_SESSION[$this->SessionName]);
        foreach ($this->session as $key => $val) {
            unset($this->session[$key]);
        }
        $_SESSION[SESSION_PREFIX . $this->productWareHouseControl] = false;
        return $this->setSessionCart();
    }

    /*
     * sepetteki ler hakkında bu mini karta json verisi gönderir
     * ajax sepete ekle ye gibi minik olan alana bilgi gondermeye yarar
     * @return mixed
     * TODO : bak buna fazlalık aslında birisi ve bootsrap lı bi yapı gerekiyor
     */
    private function viewCartTableMiniJSON()
    {
        if (sizeof($this->session > 0)) {
            $products = '<table>
		<tbody>
		';
            foreach ($this->session as $id => $item) {
                // $this->session [$id] ['ToplamFiyat'] = ($this->session [$id] ['Fiyat'] * $this->session [$id] ['ToplamAdet']);
                $products .= '<tr><td class="image"><a href="' . $item['URL'] . '">
				<img title="' . $item['URL'] . '" alt="' . $item['UrunAdi'] . '" width="43" height="43" src="' . $item['ResimURL'] . '"></a></td>
                <td class="name">
                <a href="' . $item['URL'] . '">
				<div style="width: 115px; height: 60px; overflow: hidden;">"' . $item['UrunAdi'] . '"</div>
				</a></td>
				<td class="quantity" style="width: 90px;">
				<span class="price2">' . ($item['Fiyat']) . 'x</span>' . $item['ToplamAdet'] . ' ' .($item['StokBirimi']) . '
				</td>
				<td class="total" style="width: 90px;">' . ($item['ToplamFiyat']) . ' dollar</td>
					<td class="remove"><a  onclick="sepeti_sil(' . $id . ',true );" href="javascript: void(0)"  class="sil">
			    <img title="Kaldır" alt="Kaldır" src="' . $this->public_path . '/public/img/remove.png"></a></td></tr>';
            }
            $products .= "
		</tbody>
		</table>";
            
            return $products;
        }

        else {
            return "<h1>Alışveriş Sepetiniz Boş</h1><br> Sepetiniz Boş";
        }
    }

    /*
     * sepetteki ler hakkında bu mini karta json verisi gönderir
     * ajax sepete ekle ye gibi minik olan alana bilgi gondermeye yarar
     * @return mixed
     */
    private function SonEKlenenUrunFiyatDegeri()
    {
        if (sizeof($this->session > 0)) {
            $id = $this->lastAdded;
            return ($this->session[$id]['ToplamFiyat']) . ' $';
        } else {
            return null;
        }
    }

    /*
     * sepete eklenen son urunun sepettki adetini verir
     * ajax sepete ekle ye gibi minik olan alana bilgi gondermeye yarar
     * @param int $id eğer id değeri false ise bu cart içindeki getJSON dan tetiklenmesi içindir ,
     * id farklı değer ise sepet controller içinden tetiklenmesi gerekiyor anlamına gelir
     * @return mixed
     */
    public function SonEKlenenUrunAdeti($id = false)
    {
        if ($id != false) {
            $id = $id;
        } else {
            $id = $this->lastAdded;
        }
        if (sizeof($this->session > 0)) {
            
            return $this->session[$id]['ToplamAdet'];
        } else {
            return null;
        }
    }

    /*
     * sepete eklenen son urunun stok adetini verir
     * ajax sepete ekle ye gibi minik olan alana bilgi gondermeye yarar
     * @return mixed
     */
    private function SonEKlenenUrunStokAdeti()
    {
        $id = $this->lastAdded;
        if (sizeof($this->session > 0)) {
            return $this->session[$id]['StokMiktari'];
        } else {
            return null;
        }
    }

    /*
     * sepetteki ler hakkında bilgi verir
     * ajax sepete ekle ye gibi minik olan alana dışarı bilgi gondermeye yarar
     * en üst alandaki header view içindeki bilgilerin görüneceği alandır
     * @param $type json a cıktı gonderilecek mi
     * @return mixed
     */
    public function MiniSepet()
    {
        if (sizeof($this->session) > 0) {
            $products = '<div class="mini-cart-info"><table>	<tbody>	';
            
            
            $products_uyarili = '       <table class="pull-left">
            <tbody>
            
            <tr>
            <td class="right"><b>Özür dileriz '.SEPETTE_OLACAK_MAXIMUM_FIYAT.' TL altındaki siparişlerinizi kabul etmiyoruz</b> </td>
       
            </tr>
            </tbody>
            </table> <table>
		<tbody>
		';
            
            
            foreach ($this->session as $id => $item) {
                // $this->session [$id] ['ToplamFiyat'] = ($this->session [$id] ['Fiyat'] * $this->session [$id] ['ToplamAdet']);
                $products .= '<tr id="mini-cart' . $id . '"><td class="image"><a href="' . $item['URL'] . '">
				<img title="' . $item['URL'] . '" alt="' . $item['UrunAdi'] . '" width="43" height="43" src="' . $item['ResimURL'] . '"></a></td>
                <td class="name">
                <a href="' . $item['URL'] . '">
				<div style="width: 115px; height: 60px; overflow: hidden;">"' . $item['UrunAdi'] . '"</div>
				</a></td>
				<td class="quantity" style="width: 90px;">
				<span class="price2">' . $this->TL_Format($item['Fiyat']) . 'x</span>' . $item['ToplamAdet'] . ' ' . \Lib\Tools::stok_birimleri($item['StokBirimi']) . '
				</td>
				<td class="total" style="width: 90px;">' . $this->TL_Format($item['ToplamFiyat']) . ' TL</td>
				<td class="remove"><a  onclick="sepeti_sil(' . $id . ',true );" href="javascript: void(0)"  class="sil">
			    <img title="Kaldır" alt="Kaldır" src="' . $this->public_path . '/public/img/remove.png"></a></td></tr>';
            }
            $products .= "
		</tbody>
		</table>
         </div>";
            
            $products .= '<div class="mini-cart-total">' . $this->viewCartTablePrice() . '</div>
            <div class="checkout">
			<a href="javascript: void(0)" class="button sepetikapat"><i class="fa fa-times" aria-hidden="true"></i> Kapat</a> &nbsp;
			<a href="/sepetim/" class="button">Sepetim</a> &nbsp;
			<a class="button" href="/adres">Ödeme Yap</a>
			</div>';
            
            return $products;
        }  // direk olarak sepet boş uyarısı vermesi içindir
else {
            return '<div class="mini-cart-info"><h3 style="text-align:center;color:#000">Alışveriş Sepetiniz Boş</h1></div>
                <div class="mini-cart-total"></div> 
				<div style="display:none" class="checkout">
					<a href="javascript: void(0)" class="button sepetikapat"><i class="fa fa-times" aria-hidden="true"></i> Kapat</a> &nbsp;
				<a href="/sepetim/" class="button">Sepetim</a> &nbsp;
			<a class="button" href="/adres">Ödeme Yap</a>
			</div>';
        }
    }

    /*
     * sepet sayfası na basılıcak yerdir
     * sepetteki ler hakkında bilgi verir
     * $liste nin anlamı sadece cart ın listelenmesinin isteyen istekler olabilir mesela odeme
     * içinden gelen bu $sepet->viewCartTableFull('liste'); istek gibi
     * @return mixed
     */
    public function viewCartTableFull($liste = "b")
    {
        if (sizeof($this->session) > 0) {
            
            if ($liste != "liste")
                $class = '';
            else
                $class = 'class="body-wrap" border="1" cellspacing="0" cellpadding="0"';
            
            $products = '
        <div class="cart-info">
       	<table ' . $class . '>
            <thead>
              <tr>
                <td class="image">Ürün Görseli</td>
                <td class="name">Ürün Açıklaması</td>
          
                <td class="quantity">Adet</td>
                 <td class="unit">Birim</td>
                <td class="price">Birim Fiyat</td>
                <td class="total">Toplam</td>
              </tr>
            </thead>
            <tbody class="sepetsatirlari">';
            
            foreach ($this->session as $id => $item) {
                // $this->session [$id] ['ToplamFiyat'] = ($this->session [$id] ['Fiyat'] * $this->session [$id] ['ToplamAdet']);
                
                // sil diğer kodları <a onclick="sepeti_sil_sepetim(' . $id . ',true );" href="javascript: void(0)" class="sil">
                
                if ($item['ResimURL'] == '') {
                    $resim = BISLEM_RESIM_BULUNAMADİ;
                } else {
                    $resim = $item['ResimURL'];
                }
                
                if ($liste != "liste") {
                    $input='';
                    $item['StokMiktari']='';
                    if ($item['StokBirimi'] == 'ADET') {

   $input = '<div class="input-group spinner table-spinner" data-trigger="spinner">
    <input type="text" class="form-control quantity text-center urun_adeti" value="' . $item['ToplamAdet'] . '"
                        id="urun_adeti_' . $item['UrunID'] .'" data-min="1" data-max="' . $item['StokMiktari'] . '" data-rule="quantity">
                        <div class="input-group-addon">
                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>
                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>
                            </div>
                        </div>
                        ';
                    }
                    //Kilogram //KİLOGRAM
                    if ($item['StokBirimi'] == 'Kilogram') {

                        $input = '<div class="input-group spinner table-spinner" data-trigger="spinner">
    <input type="text" class="form-control quantity text-center urun_adeti" value="' . $item['ToplamAdet'] . '"
                        id="urun_adeti_' . $item['UrunID'] .'" data-step="0.10" data-min="0.10" data-max="' . $item['StokMiktari'] . '" data-rule="currency">
                        <div class="input-group-addon">
                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>
                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>
                            </div>
                        </div>
                        ';




                    }
                    
                    $deger = '
                      
                    <div class="input-group">
                         ' . $input . '
					</div>
                            
                            <a href="/sepet/sepet_sil/' . $id . '"  class="sil">
			   						 <img title="Kaldır" alt="Kaldır" src="' . $this->public_path . '/public/img/remove.png" onclick="var r = confirm(\'Emin misiniz?\'); if (r == true) return true; else return false;">';
                } else {
                    $deger = $item['ToplamAdet'];
                }
                
                $products .= '<tr id="full-cart' . $id . '" class="sepetsatiri">
                <td class="image">
				<a href="' . $item['URL'] . '">
				    
                <img src="' . $resim . '" style="height:35px;" alt="' . $item['UrunAdi'] . '" title="' . $item['UrunAdi'] . '"></a>
                    
                </td>
                <td class="name">
                <a href="' . $item['URL'] . '">' . $item['UrunAdi'] . '</a>
                </td>
         
                <td class="quantity">
            			' . $deger . '
			    </td>
            			             <td class="unit">
            			' . ($item['StokBirimi']) . '
			    </td>
			    <td class="price">' . ($item['Fiyat']) . ' TL</td>
                <td id="total_' . $item['UrunID'] . '" class="total">' .($item['ToplamFiyat']) . ' TL</td>
              </tr>';
            }
            $products .= ' </tbody>
          </table>
        </div>
   ';
            return $products;
        } else {
            return '<h1>Alışveriş Sepetiniz Boş</h1><br>
					<a href="/">Alışverişe devam etmek için buraya tıklayınız.</a>
					';
        }
    }

    /*
     * sepetteki ler hakkında bilgi verir
     * @return mixed
     */
    public function viewCartTablePrice()
    {
        $products = "<table>
		<tbody>
		";
        if (sizeof($this->session) > 0) {
            
            $tot = $this->cartCount();
            $products .= '<tr>
							<td class="right"><b>Toplam Ürün:</b></td>
							<td class="right">' . $tot["toplam_urun"] . ' Ürün</td>
							</tr>
							<tr>
							<td class="right"><b>Toplam Adet:</b></td>
							<td class="right">' . $tot["toplam_adet"] . ' Adet</td>
							</tr>
							<tr class="price2">
							<td class="right"><b>Toplam Tutar:</b></td>
							<td class="right">' . ($this->SubTotal) . ' TL</td>
							<tr>';
        } else { // sepet boş ise
            $products .= '
							<tr>
							<td colspan="2" ><h3 style="text-align:center">Sepetinizde ürün bulunmamaktadır</h3></td>
							</tr>
';
        }
        $products .= "
		</tbody>
		</table>";
        
        return $products;
    }

    /*
     * sepetteki ler hakkında bilgi verir
     * @return mixed
     */
    public function viewCart()
    {
        if (isset($_SESSION[$this->SessionName])) {
            if (count($this->session) > 0) {
                echo '<pre>';
             //   print_r($this->session);
                echo '</pre>';
                // print_r($_SESSION[$this->SessionName]);
            }
        }
    }

    /*
     * sepeti json olarak verir
     * @return array json doner
     */
    public function getJSON()
    {
        if (! $_SESSION[SESSION_PREFIX . $this->productWareHouseControl]) {
            if (sizeof($this->session) > 0) {
                $tot = $this->cartCount();
                $json = array(
                    "DURUM" => 'ok',
                    // "SonEKlenenUrunStokAdeti" => $this->SonEKlenenUrunStokAdeti(),/*burasının amacı sepetim sayfasında degisken_max_adetine deger vermesi içindir */
                    "SepetSatirlari" => $this->viewCartTableMiniJSON(),
                    "SonEKlenenUrunFiyatDegeri" => $this->SonEKlenenUrunFiyatDegeri(),
                    "SonEKlenenUrunAdeti" => $this->SonEKlenenUrunAdeti(),
                    "SepetToplamKodu" => $this->viewCartTablePrice(),
                    "SepetUst" => $tot["toplam_adet"] . ' Adet <strong class="price2">' . $this->SubTotal . ' TL</strong>',
                    "SepetToplamFiyat" => $this->updateSubTotal() . ' TL'
                );
                return json_encode($json);
            }

            else {
                $json = array(
                    "DURUM" => 'bos',
                    "SepetSatirlari" => null,
                    "SonEKlenenUrunFiyatDegeri" => null,
                    "SonEKlenenUrunAdeti" => null,
                    "SepetToplamKodu" => $this->viewCartTablePrice(),
                    "SepetUst" => "",
                    'SepetLimit' => true,
                    "SepetToplamFiyat" => ""
                );
                return json_encode($json);
            }
        } else {
            
            $json = array(
                "DURUM" => 'stok_asimi',
                "SepetSatirlari" => $this->viewCartTableMiniJSON(),
                "SonEKlenenUrunAdeti" => $this->SonEKlenenUrunAdeti(),
                "SonEKlenenUrunFiyatDegeri" => $this->SonEKlenenUrunFiyatDegeri(),
                "SepetToplamKodu" => $this->viewCartTablePrice(),
                'SepetLimit' => true,
                "SepetUst" => $tot["toplam_adet"] . ' Adet <strong class="price2">' . $this->SubTotal . ' TL</strong>',
                "SepetToplamFiyat" => $this->SubTotal . ' TL'
            );
            return json_encode($json);
        }
    }

    /*
     * sepetteki ürün toplamı hakkında bilgi verir
     * sepette kaç Adet ürün ve kaç ürün var
     * @return array
     */
    public function cartCount()
    {
        // print_r(array_keys($this->sess));
        if (count($this->session) > 0) {
            $ToplamAdet[] = array();
            foreach ($this->session as $val2) {
                $ToplamAdet[] = $val2['ToplamAdet'];
            }
            
            $toplam_urun = count($this->session); // sadecce tekil ürünü verir
            
            $toplam_adet = array_sum($ToplamAdet); // tumunun toplamını verir yani bir ürünü bi kaç sepete atmış olablir onları da sayar
            
            return array(
                "toplam_urun" => $toplam_urun,
                "toplam_adet" => $toplam_adet
            );
        } else {
            return array(
                "toplam_adet" => 0
            );
        }
    }

    /*
     * sepetteki ürün hakkında bilgiler verir
     * toplam urun
     * toplam adet
     * toplam tutar
     * @return array
     */
    public function cartInfo()
    {
        if (sizeof($this->session) > 0) {
            $tot = $this->cartCount();
            $products = array(
                'toplam_urun' => $tot["toplam_urun"],
                'toplam_adet' => $tot["toplam_adet"],
                'toplam_tutar' => $this->tr_number($this->SubTotal)
            );
        } else { // sepet boş ise
            $products = array(
                'toplam_urun' => 0,
                'toplam_adet' => 0,
                'toplam_tutar' => 0
            );
        }
        
        return $products;
    }



    /*
     * session objesini verir [ object = session ]
     * bu kısım construct oluşturularak gelir
     *
     */
    protected function getSessionCart()
    {
        // $this->session = isset ( $_SESSION [$this->SessionName] ) ? $_SESSION [$this->SessionName] : array (); // org
        if (! isset($_SESSION[$this->SessionName]) && (isset($_COOKIE[$this->SessionName]))) {
            $this->session = unserialize(base64_decode($_COOKIE[$this->SessionName]));
        } else {
            $this->session = isset($_SESSION[$this->SessionName]) ? $_SESSION[$this->SessionName] : array(); // org
        }
        
        $this->updateSubTotal(); // Fiyatları güncelle
                                 // echo "<pre>"; print_r($this->session); echo "<pre>";
        return true;
    }
    
    // sessoin lar set edilir [ session = object ]
    // en önemli yer
    protected function setSessionCart()
    {
        $_SESSION[$this->SessionName] = $this->session;
        $this->updateSubTotal(); // Fiyatları güncelle
        
        /*
         * if ($this->cookie_enabled) {
         * $arrays = base64_encode ( serialize ( $_SESSION [$this->SessionName] ) );
         * setcookie ( $this->SessionName, $arrays, time () + $this->Cookie_Date, '/' );
         * }
         */
        
        return true;
    }
}



// ////class sonu
/*
$cart = new STNC_cart ( "STNC_ShopCart" );

if (isset ( $_POST ['gonder1'] ) == "ekle 34") {
	// echo "sepete eklendi <br>";
	// $cart->addToCart("45",1);
	
	$data = array (
			'UrunID' => 34,
			'UrunAdi' => "çikolata  ",
			'Resim' => "biskuvi.jpg",
			'ResimURL' => "biskuvi.jpg",
			'URL' => "biskuvi.jpg",
			'Fiyat' => 40.99,
			"ToplamAdet" => 1,
			"ToplamFiyat" => ""
	);
	$cart->addToCart ( "34", $data );
}

if (isset ( $_POST ['gonder2'] ) == "ekle 35") {
	$data = array (
			'UrunID' => 35,
			'UrunAdi' => "biskuvi ",
			'Resim' => "biskuvi.jpg",
			'ResimURL' => "biskuvi.jpg",
			'URL' => "biskuvi.jpg",
			'Fiyat' => 2963.50, //2.963,50
			"ToplamAdet" => 1,
			"ToplamFiyat" => ""
	);
	
	echo "sepete eklendi  <br>";
	// $cart->addToCart("45",1);
	$cart->addToCart ( "35", $data );
	$cart->discount = 35;
	$cart->bonusProduct = 11;
}

if (isset ( $_POST ['gonder3'] ) == "ekle 36") {
	echo "sepete eklendi  <br>";
	$data = array (
			'UrunID' => 36,
			'UrunAdi' => "laptop ",
			'Resim' => "biskuvi.jpg",
			'ResimURL' => "biskuvi.jpg",
			'URL' => "biskuvi.jpg",
			'Fiyat' => 2177,
			"ToplamAdet" => 1,
			"ToplamFiyat" => ""
	);
	// $cart->addToCart("45",1);
	$cart->addToCart ( "36", $data );
	// echo "son ekledin urun " . $cart->lastAdded;
}

if (isset ( $_POST ['clear'] ) == "sepeti boşalt") {
	$cart->emptyCart ();
}

if (isset ( $_POST ["sil36"] ) == "36 id li ürünü sil") {
	$cart->removeCart ( 36 );
}

if (isset ( $_POST ["Fiyat"] ) == "Fiyat") {
	echo $cart->TL_Format ( $cart->updateSubTotal () );
}

// echo $cart->getJSON ();

// echo $cart->viewCart ();

echo $cart->viewCartTableFull ();
echo "<br>";
echo $cart->TL_Format ( $cart->SubTotal );
// echo $cart->viewCartTableMini ();
?>
<link rel="stylesheet" type="text/css" href="sytle.css" />
<form method="post" action="">
	<input type="submit" name="gonder1" value="ekle 34" /> <input
		type="submit" name="gonder2" value="ekle 35" /> <input type="submit"
		name="gonder3" value="ekle 36" /> <input type="submit"
		value="36 id li ürünü sil" name="sil36" /> <input type="submit"
		name="Fiyat" value="Fiyat" /> <input type="submit" name="clear"
		value="sepeti boşalt" />
</form>
*/
