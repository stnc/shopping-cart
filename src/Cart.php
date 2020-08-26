<?php

/**
 * @author selman.tunc
 *
 */
namespace Stnc\ShoppingCart;

/**
 * Alışveriş uygulamalrı için sepet  
 * Copyright (c) 2015 - 2020
 *
 * Author(s): Selman TUNÇ www.selmantunc.com.tr 
 * <selmantunc@gmail.com>
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



    /**
     * cookie set 
     *
     * @var $setCookie
     */
    public $setCookie = true;

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
     * The option of whether to group the same product if there is one
     * 
     * tr: aynı üründen var ise onu gruplama yapıp yapmayacağı seçeneği
     * yani sepete kraker ekletiniz id si :10 tekrar aynı id si 10 olan 
     * ürünü eklediniz kraker tek satırda kraker * 2 şeklinde mi gösterilsin 
     * 
     * yoksa 
     * kraker 1 adet 
     * kraker 1 adet mi gosterilsin onun içindir 
     *
     * @var boolean $groups
     */
    public $groups = true;

    /**
     * __construct
     * session name set 
     *
     * @param string $value
     *            puplic klasoru
     */
    public function __construct($sessionName_ )
    {
        $this->sessionName = $sessionName_;
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
                $this->addSessionCart();
                break;
        }
    }


    public function addToCart($id, $data, $dataType = 'noajax')
    {
        $this->lastAdded = $id;
        if ($this->groups) {
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
     * delete product 
     * tr: sepetten ürünü çıkartır
     *
     * @param int $id
     *
     */
    public function removeCart($id)
    {
 
        if (count($this->session) > 0) {
            if (array_key_exists($id, $this->session)) {     
                 unset($this->session[$id]);
                //  unset ($this->session[$id]['totalEach']);
                //  unset ($this->session[$id]['price']);
            }
          //  unset($this->session[$id]);
        }
        return $this->setSessionCart();
    }


    /**
     * empty cart
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
        return $this->setSessionCart();
    }

  
    /*
     * gives information about the total of items in the basket, How many products and how many products are in the basket
     * 
     * tr: sepetteki ürün toplamı hakkında bilgi verir
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

    //TODO: ayrıca bunun istenen ürün bilgisi veren bolumlu halide olmalı (cartCount fonk ile ne farkı var bakılacak )
    /*
    * gives information about the product in the basket
       total product
       total number
       total amount 
     *CART INFORMATION 
      sepetteki ürün hakkında bilgiler verir
      toplam urun
      toplam adet
      toplam tutar
     * @return array
     */
    public function cartInfo()
    {
        if (is_array($this->session) && sizeof($this->session) > 0) {
            $tot = $this->cartCount();
            $products = array(
                'totalProduct' => $tot["totalProduct"],
                'totalPiece' => $tot["totalPiece"],
                'totalPrice' => $this->subTotal,
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
     * cart to array 
     * TODO: bunun json metoduda olsun 
     * @return mixed
     */
    public function getArray()
    {
        if (isset($_SESSION[$this->sessionName])) {
            if (count($this->session) > 0) {

                return $this->session;
            }
        }
    }


  /*
     * cart to json 
     * @return array json doner
     */
    public function getJSON()
    {
        if (is_array($this->session) && sizeof($this->session) > 0) {
            
                $json = array(
                     "status" => 'ok',
                     "cartItems" => $this->getArray(),
                     "cartTotalPrice" => $this->subTotal ,
                     "cartItemPiece" =>  $this->cartCount(),
                );
            } else {
                $json = array(
                     "status" => 'empty',
                     "cartItems" => $this->getArray(),
                     "cartTotalPrice" => $this->subTotal ,
                     "cartItemPiece" =>  $this->cartCount(),
                );
            }
            return json_encode($json);
        
    }
    
    /**
     * 
     * cart price calculator
     * sepetin Pricelarını hesapla
     *
     * @return float
     */
    public function updatesubTotal()
    {

       // print_r($_SESSION[$this->sessionName]);
        $totalPrice = 0;
        if (is_array($this->session) && sizeof($this->session) > 0) {
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


    /*
     * get session cart info 
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

    /*
     *gives the price information of the last added product 
     * son eklenen ürünün fiyat bilgisini verir
     * @return mixed
     */
    protected function getLastCartPriceInfo()
    {
        if (sizeof($this->session > 0)) {
            $id = $this->lastAdded;
            return ($this->session[$id]['totalPrice']) . ' $';
        } else {
            return null;
        }
    }

    /*
     * gives the number of the last product added to the basket in the basket
     * tr: sepete eklenen son urunun sepettki adetini verir
     * ajax sepete ekle ye gibi minik olan alana bilgi gondermeye yarar
     * @param int $id eğer id değeri false ise bu cart içindeki getJSON dan tetiklenmesi içindir ,
     * id farklı değer ise sepet controller içinden tetiklenmesi gerekiyor anlamına gelir
     * @return mixed
     */
    public function getLastCartPiece($id = false)
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
     * gives the stock quantity of the last product added to the basket
     * sepete eklenen son urunun stok adetini verir
     * @return mixed
     */
    private function getLastCartStockPiece()
    {
        $id = $this->lastAdded;
        if (sizeof($this->session > 0)) {
            return $this->session[$id]['amountOfStock'];
        } else {
            return null;
        }
    }

  
   
    /*
     * session name 
     * @return string 
     */
    public function getSessionName()
    {
        if (isset($_SESSION[$this->sessionName])) {
            if (count($this->session) > 0) {
                return ($_SESSION[$this->sessionName]);
            }
        }
    }


    // sessoin set
    // very important 
    protected function setSessionCart()
    {
       // print_r($_SESSION);
        $_SESSION[$this->sessionName] = $this->session;
   // print_r(    $_SESSION[$this->sessionName]);
        $this->updatesubTotal(); // Priceları güncelle

        //   if ($this->setCookie) {
        //   $arrays = base64_encode ( serialize ( $_SESSION [$this->sessionName] ) );
        //   setcookie ( $this->sessionName, $arrays, time () + $this->cookieDate, '/' );
        //   }

        return true;
    }
}
