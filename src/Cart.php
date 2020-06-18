<?php

/**
 * @author selman.tunc
 *
 */
namespace stnc\shoppingCart;

/**
 * Price hesaplamaları ve o türden özellikdeki bilgileri barındıracak
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
     * @var string $sessionName
     */
    protected $sessionName = null;

    /**
     * son eklenen ürün id değeri
     *
     * @var int $lastAdded
     */
    public $lastAdded = null;

    /**
     * genel parasal toplamlar yapılır
     *
     * @var float $subTotal
     */
    public $subTotal = null;

    /**
     * resim klasoruleri için varsayaılan yolu set eder
     *
     * @var string $PUBLIC_PATH
     */
    private $publicPath = null;

    /**
     * aynı üründen var ise onu gruplama yapıp yapmayacağı seçeneği
     * yani sepete kraker ekletiniz id si :10 tekrar aynı id si 10 olan 
     * ürünü eklediniz kraker tek satırda kraker * 2 şeklinde mi gösterilsin 
     * 
     * yoksa 
     * kraker 1 adet 
     * kraker 1 adet mi gosterilsin onun içindir 
     *
     * @var boolean $eachAdd
     */
    private $eachAdd = false;

    /**
     * kurucu ayarlar
     * session name set edeilir
     *
     * @param string $value
     * @param string $publicPath
     *            puplic klasoru
     */
    public function __construct($sessionName, $publicPath)
    {
        $this->$sessionName = $sessionName;
        $this->public_path = $publicPath;
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
        $_SESSION[$this->productWareHouseControl] = false;
        if (count($this->session) > 0) {
            if (array_key_exists($id, $this->session)) {
                unset($this->session[$id]);
                // unset ($this->session[$id]['totalEach']);
                // unset ($this->session[$id]['price']);
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
                $this->session[$id]['totalEach'] -= $adet;
                /* $this->session[$id]['totalEach'] -= 1; */
                $this->session[$id]['totalPrice'] = ($this->session[$id]['price'] * $this->session[$id]['totalEach']);
            }
        }
        return $this->setSessionCart();
    }

    public function addToCart($id, $data, $dataType = 'noajax')
    {
        
        //   echo '<pre>';
        // //   print_r($this->session[$id]);
        //   print_r($data);
        //   echo '</pre>';
         
        $this->lastAdded = $id;
        if ($this->eachAdd) {
            // urun zaten eklenmişse ve tekrar gelirse totalEachini artır
            if (array_key_exists($id, $this->session)) {
                $total = $data["totalEach"];
                // $this->session[$id]['totalEach'] = $this->v[$id]['totalEach'] + $this->session[$id]['totalEach'];
                $this->session[$id]['totalEach'] += $total;
                $this->session[$id]['totalPrice'] = ($this->session[$id]['price'] * $this->session[$id]['totalEach']);
            } else { 
                /* yeni urunse ekle direk ekle */
                $this->session[$id] = $data;
                //Bir dizinin başlangıcına bir veya daha fazla eleman ekler fakat dizi keyleri onemli o yuzden bekleyecek
                // array_unshift( $this->session[$id],$data);
            }
        } else {
           
            $this->session[$id+rand(1,500)] = $data;
            
        }

     //$_SESSION[$this->$sessionName][$id]['totalEach'] += $_SESSION[$this->$sessionName][$id]['totalEach'] + $total; echo $_SESSION[$this->$sessionName][$id]['totalEach'];
        return $this->setSessionCart();
    }

    /**
     * sepetin Pricelarını hesapla
     *
     * @return float
     */
    public function updatesubTotal()
    {
        $totalPrice = 0;
        if (sizeof($this->session) > 0) {
            foreach ($this->session as $id => $item) {

                //php 7 http://php.net/manual/en/migration71.other-changes.php
                if (is_numeric($item['totalPrice'])) {
                    $totalPrice += $totalPrice + $item['totalPrice'];
                }

                // $totalPrice += $item['totalPrice'] ;//silme
                $this->subTotal = $totalPrice;
            }
            return ($totalPrice);
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
        unset($_SESSION[$this->$sessionName]);
        foreach ($this->session as $key => $val) {
            unset($this->session[$key]);
        }
        $_SESSION[$this->productWareHouseControl] = false;
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
                // $this->session [$id] ['totalPrice'] = ($this->session [$id] ['price'] * $this->session [$id] ['totalEach']);
                $products .= '<tr><td class="image"><a href="' . $item['URL'] . '">
				<img title="' . $item['URL'] . '" alt="' . $item['UrunAdi'] . '" width="43" height="43" src="' . $item['ResimURL'] . '"></a></td>
                <td class="name">
                <a href="' . $item['URL'] . '">
				<div style="width: 115px; height: 60px; overflow: hidden;">"' . $item['UrunAdi'] . '"</div>
				</a></td>
				<td class="quantity" style="width: 90px;">
				<span class="price2">' . ($item['price']) . 'x</span>' . $item['totalEach'] . ' ' . ($item['StokBirimi']) . '
				</td>
				<td class="total" style="width: 90px;">' . ($item['totalPrice']) . ' dollar</td>
					<td class="remove"><a  onclick="sepeti_sil(' . $id . ',true );" href="javascript: void(0)"  class="sil">
			    <img title="Kaldır" alt="Kaldır" src="' . $this->public_path . '/public/img/remove.png"></a></td></tr>';
            }
            $products .= "
		</tbody>
		</table>";

            return $products;
        } else {
            return "<h1>Alışveriş Sepetiniz Boş</h1><br> Sepetiniz Boş";
        }
    }

    /*
     * sepetteki ler hakkında bu mini karta json verisi gönderir
     * ajax sepete ekle ye gibi minik olan alana bilgi gondermeye yarar
     * @return mixed
     */
    private function SonEKlenenUrunPriceDegeri()
    {
        if (sizeof($this->session > 0)) {
            $id = $this->lastAdded;
            return ($this->session[$id]['totalPrice']) . ' $';
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

            return $this->session[$id]['totalEach'];
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
            return $this->session[$id]['amountOfStock'];
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
            <td class="right"><b>Özür dileriz ' . CART_MAX_PRICE . ' TL altındaki siparişlerinizi kabul etmiyoruz</b> </td>

            </tr>
            </tbody>
            </table> <table>
		<tbody>
		';

            foreach ($this->session as $id => $item) {
                // $this->session [$id] ['totalPrice'] = ($this->session [$id] ['price'] * $this->session [$id] ['totalEach']);
                $products .= '<tr id="mini-cart' . $id . '"><td class="image"><a href="' . $item['URL'] . '">
				<img title="' . $item['URL'] . '" alt="' . $item['UrunAdi'] . '" width="43" height="43" src="' . $item['ResimURL'] . '"></a></td>
                <td class="name">
                <a href="' . $item['URL'] . '">
				<div style="width: 115px; height: 60px; overflow: hidden;">"' . $item['UrunAdi'] . '"</div>
				</a></td>
				<td class="quantity" style="width: 90px;">
				<span class="price2">' . $this->TL_Format($item['price']) . 'x</span>' . $item['totalEach'] . 'KG
				</td>
				<td class="total" style="width: 90px;">' . $this->TL_Format($item['totalPrice']) . ' TL</td>
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
        } // direk olarak sepet boş uyarısı vermesi içindir
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
    public function viewCartTableFull()
    {
        if (sizeof($this->session) > 0) {

            $class = 'class="table table-striped"';

            $products = '
        <div class="cart-info">
       	<table ' . $class . '>
            <thead>
              <tr>
                <td class="image">Ürün Görseli</td>
                <td class="name">Ürün Açıklaması</td>

                <td class="quantity">Adet</td>
                 <td class="unit">Birim</td>
                <td class="price">Birim Price</td>
                <td class="total">Toplam</td>
              </tr>
            </thead>
            <tbody class="sepetsatirlari">';

            foreach ($this->session as $id => $item) {
                // $this->session [$id] ['totalPrice'] = ($this->session [$id] ['price'] * $this->session [$id] ['totalEach']);

                // sil diğer kodları <a onclick="sepeti_sil_sepetim(' . $id . ',true );" href="javascript: void(0)" class="sil">

                if ($item['ResimURL'] == '') {
                    $resim = noPicture;
                } else {
                    $resim = $item['ResimURL'];
                }

                $input = '';
                $item['amountOfStock'] = '';
                if ($item['StokBirimi'] == 'ADET') {

                    $input = '<div class="input-group spinner table-spinner" data-trigger="spinner">
    <input type="text" class="form-control quantity text-center urun_adeti" value="' . $item['totalEach'] . '"
                        id="urun_adeti_' . $item['UrunID'] . '" data-min="1" data-max="' . $item['amountOfStock'] . '" data-rule="quantity">
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
    <input type="text" class="form-control quantity text-center urun_adeti" value="' . $item['totalEach'] . '"
                        id="urun_adeti_' . $item['UrunID'] . '" data-step="0.10" data-min="0.10" data-max="' . $item['amountOfStock'] . '" data-rule="currency">
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

                            <a href="?clear=' . $id . '"  class="sil">
			   						 <img title="Kaldır" alt="Kaldır" src="' . $this->public_path . '/public/img/remove.png" onclick="var r = confirm(\'Emin misiniz?\'); if (r == true) return true; else return false;">';

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
			    <td class="price">' . ($item['price']) . ' TL</td>
                <td id="total_' . $item['UrunID'] . '" class="total">' . ($item['totalPrice']) . ' TL</td>
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
							<td class="right">' . ($this->subTotal) . ' TL</td>
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
        if (isset($_SESSION[$this->sessionName])) {
            if (count($this->session) > 0) {
                echo '<pre>';
                print_r($this->session);
                echo '</pre>';
                print_r($_SESSION[$this->$sessionName]);
            }
        }
    }

    /*
     * sepeti json olarak verir
     * @return array json doner
     */
    public function getJSON()
    {
        if (!$_SESSION[$this->productWareHouseControl]) {
            if (sizeof($this->session) > 0) {
                $tot = $this->cartCount();
                $json = array(
                    "DURUM" => 'ok',
                    // "SonEKlenenUrunStokAdeti" => $this->SonEKlenenUrunStokAdeti(),/*burasının amacı sepetim sayfasında degisken_max_adetine deger vermesi içindir */
                    "SepetSatirlari" => $this->viewCartTableMiniJSON(),
                    "SonEKlenenUrunPriceDegeri" => $this->SonEKlenenUrunPriceDegeri(),
                    "SonEKlenenUrunAdeti" => $this->SonEKlenenUrunAdeti(),
                    "SepetToplamKodu" => $this->viewCartTablePrice(),
                    "SepetUst" => $tot["toplam_adet"] . ' Adet <strong class="price2">' . $this->subTotal . ' TL</strong>',
                    "SepettotalPrice" => $this->updatesubTotal() . ' TL',
                );
                return json_encode($json);
            } else {
                $json = array(
                    "DURUM" => 'bos',
                    "SepetSatirlari" => null,
                    "SonEKlenenUrunPriceDegeri" => null,
                    "SonEKlenenUrunAdeti" => null,
                    "SepetToplamKodu" => $this->viewCartTablePrice(),
                    "SepetUst" => "",
                    'SepetLimit' => true,
                    "SepettotalPrice" => "",
                );
                return json_encode($json);
            }
        } else {

            $json = array(
                "DURUM" => 'stok_asimi',
                "SepetSatirlari" => $this->viewCartTableMiniJSON(),
                "SonEKlenenUrunAdeti" => $this->SonEKlenenUrunAdeti(),
                "SonEKlenenUrunPriceDegeri" => $this->SonEKlenenUrunPriceDegeri(),
                "SepetToplamKodu" => $this->viewCartTablePrice(),
                'SepetLimit' => true,
                "SepetUst" => $tot["toplam_adet"] . ' Adet <strong class="price2">' . $this->subTotal . ' TL</strong>',
                "SepettotalPrice" => $this->subTotal . ' TL',
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
            $totalEach[] = array();
            foreach ($this->session as $val2) {
                $totalEach[] = $val2['totalEach'];
            }

            $toplam_urun = count($this->session); // sadecce tekil ürünü verir

            $toplam_adet = array_sum($totalEach); // tumunun toplamını verir yani bir ürünü bi kaç sepete atmış olablir onları da sayar

            return array(
                "toplam_urun" => $toplam_urun,
                "toplam_adet" => $toplam_adet,
            );
        } else {
            return array(
                "toplam_adet" => 0,
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
                'toplam_tutar' => $this->tr_number($this->subTotal),
            );
        } else { // sepet boş ise
            $products = array(
                'toplam_urun' => 0,
                'toplam_adet' => 0,
                'toplam_tutar' => 0,
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
        // $this->session = isset ( $_SESSION [$this->$sessionName] ) ? $_SESSION [$this->$sessionName] : array (); // org
        if (!isset($_SESSION[$this->sessionName]) && (isset($_COOKIE[$this->sessionName]))) {
            $this->session = unserialize(base64_decode($_COOKIE[$this->sessionName]));
        } else {
            $this->session = isset($_SESSION[$this->sessionName]) ? $_SESSION[$this->sessionName] : array(); // org
        }

        $this->updatesubTotal(); // Priceları güncelle
        // echo "<pre>"; print_r($this->session); echo "<pre>";
        return true;
    }

    // sessoin lar set edilir [ session = object ]
    // en önemli yer
    protected function setSessionCart()
    {
        $_SESSION[$this->sessionName] = $this->session;
        $this->updatesubTotal(); // Priceları güncelle

        //   if ($this->cookie_enabled) {
        //   $arrays = base64_encode ( serialize ( $_SESSION [$this->$sessionName] ) );
        //   setcookie ( $this->$sessionName, $arrays, time () + $this->Cookie_Date, '/' );
        //   }

        return true;
    }
}
