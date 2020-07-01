<?php

/**
 * @author selman.tunc
 *
 */
namespace Stnc\ShoppingCart;

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


    public $productWareHouseControl;
    /**
     * cookie aktif olacak mı
     *
     * @var $cookieEnabled
     */
    public $cookieEnabled = true;

    /**
     * cookie nin tarihi
     *
     * @var $cookieDate
     */
    public $cookieDate = 86400;
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
     * @var string $publicPath
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
    public function __construct($sessionName_, $publicPath)
    {

        $this->sessionName = $sessionName_;
        $this->publicPath = $publicPath;
        $this->getSessionCart();
    }

    // destruct - unset cart var
    public function __destruct()
    {
        unset($this->session);
    }

    // magic config
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
     * cart product delete
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
    public function AjaxRemoveCart($id, $adet = 1)
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
           
            $rndID=$id+(rand(1,500)+1);
            $this->session[ $rndID] = $data;

        
        }
//    print_r($this->session);
   //  $_SESSION[$this->sessionName][$id]['totalEach'] += $_SESSION[$this->sessionName][$id]['totalEach'] + $total; echo $_SESSION[$this->sessionName][$id]['totalEach'];
        return $this->setSessionCart();
    }

    /**
     * sepetin Pricelarını hesapla
     *
     * @return float
     */
    public function updatesubTotal()
    {

       // print_r($_SESSION[$this->sessionName]);
        $totalPrice = 0;
        if (sizeof($this->session) > 0) {
            foreach ($this->session as $id => $item) {

                //php 7 http://php.net/manual/en/migration71.other-changes.php
                if (is_numeric($item['totalPrice'])) {
                    $totalPrice +=  $item['totalPrice'];
                    //     $totalPrice += $totalPrice + $item['totalPrice']; //bu bug vermiş -----
                }

                // $totalPrice += $item['totalPrice'] ;//silme
            
            }
            $this->subTotal = $totalPrice;
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
    //     unset($_SESSION[$this->sessionName]);
    //     die()
    // ;
        foreach ($this->session as $key => $val) {
            unset($this->session[$key]);
        }
        $_SESSION[$this->productWareHouseControl] = false;
        return $this->setSessionCart();
    }

   
    /*
     * son eklenen ürünün fiyat bilgisini verir
     * @return mixed
     */
    private function addLastCartPriceInfo()
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
    public function addLastCartPiece($id = false)
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
    private function addLastCartStockPiece()
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
     * @return mixed
     */
    public function viewCart()
    {
        if (isset($_SESSION[$this->sessionName])) {
            if (count($this->session) > 0) {
                echo '<pre>';
                print_r($this->session);
                echo '</pre>';
                print_r($_SESSION[$this->sessionName]);
            }
        }
    }


    /*
     * sepetteki ler hakkında bilgi verir
     * @return mixed
     */
    public function viewCartArray()
    {
        if (isset($_SESSION[$this->sessionName])) {
            if (count($this->session) > 0) {
             
                return $this->session;

          
            }
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

            $totalProduct = count($this->session); // sadecce tekil ürünü verir

            $totalPiece = array_sum($totalEach); // tumunun toplamını verir yani bir ürünü bi kaç sepete atmış olablir onları da sayar

            return array(
                "totalProduct" => $totalProduct,
                "totalPiece" => $totalPiece,
            );
        } else {
            return array(
                "totalPiece" => 0,
            );
        }
    }

    /*
     *CART INFORMATION 
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
                'totalProduct' => $tot["totalProduct"],
                'totalPiece' => $tot["totalPiece"],
                'totalPrice' => $this->tr_number($this->subTotal),
            );
        } else { // sepet boş ise
            $products = array(
                'totalProduct' => 0,
                'totalPiece' => 0,
                'totalPrice' => 0,
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
        // $this->session = isset ( $_SESSION [$this->sessionName] ) ? $_SESSION [$this->sessionName] : array (); // org
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
       // print_r($_SESSION);
        $_SESSION[$this->sessionName] = $this->session;
   // print_r(    $_SESSION[$this->sessionName]);
        $this->updatesubTotal(); // Priceları güncelle

        //   if ($this->cookieEnabled) {
        //   $arrays = base64_encode ( serialize ( $_SESSION [$this->sessionName] ) );
        //   setcookie ( $this->sessionName, $arrays, time () + $this->cookieDate, '/' );
        //   }

        return true;
    }
}
