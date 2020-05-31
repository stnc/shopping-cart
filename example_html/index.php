<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
require_once '../vendor/autoload.php';
/*use \DB\MYSQL as dbs;
$db = new dbs\Mysql();*/
  define('SESSION_PREFIX', 'selman');
define('noPicture','noimg.png');
session_start();

use \stnc\shoppingCart\Cart;
$cart_name = 'stnc'; // sepetin session değerine bir değer atadık
$cart = new cart($cart_name, 's');

/* other call
$cart_other = new  \stnc\cart\Cart('stnc', 'ds');
print_r($cart_other);
*/
if (isset($_GET['add']) && $_GET['add']==1  ){
$data = array(
		'UrunID' => 1,
		'UrunAdi' => "biscuit  ",
		'Resim' => "biscuit.jpg",
		'ResimURL' => "",
		'URL' => "biscuit.jpg",
		'price' => 10.50,
		"totalEach" => 5,
		'StokBirimi'=>'adet',
		"totalPrice" => ""
);
$cart->addToCart(1, $data);
}

if (isset($_GET['add']) && $_GET['add']==2  ){
  $data = array(
      'UrunID' => 2,
      'UrunAdi' => "cream  ",
      'Resim' => "cream.jpg",
      'ResimURL' => "",
      'URL' => "cream.jpg",
      'price' => 30.99,
      "totalEach" => 1,
      'StokBirimi'=>'adet',
      "totalPrice" => ""
  );
  $cart->addToCart(2, $data);
  }


  if (isset($_GET['add']) && $_GET['add']==3  ){
    $data = array(
        'UrunID' => 3,
        'UrunAdi' => "chocolate  ",
        'Resim' => "chocolate.jpg",
        'ResimURL' => "",
        'URL' => "chocolate.jpg",
        'price' => 20.00,
        "totalEach" => 2,
        'StokBirimi'=>'adet',
        "totalPrice" => ""
    );
    $cart->addToCart(3, $data);
    }

    if (isset($_GET['add']) && $_GET['add']==4  ){
      $data = array(
          'UrunID' => 4,
          'UrunAdi' => "book  ",
          'Resim' => "book.jpg",
          'ResimURL' => "",
          'URL' => "book.jpg",
          'price' => 80.99,
          "totalEach" => 1,
          'StokBirimi'=>'adet',
          "totalPrice" => ""
      );
      $cart->addToCart(4, $data);
      }


if (isset($_GET['clear']) && $_GET['clear']==1  ){
   $cart->removeCart($_GET['clear']);
}

if (isset($_GET['clear']) && $_GET['clear']==2  ){
  $cart->removeCart($_GET['clear']);
}

if (isset($_GET['clear']) && $_GET['clear']==3  ){
  $cart->removeCart($_GET['clear']);
}

if (isset($_GET['clear']) && $_GET['clear']==4  ){
  $cart->removeCart($_GET['clear']);
}


if (isset($_GET['clearCart']) && $_GET['clearCart']==1  ){
  $cart->emptyCart();
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Heroic Features - Start Bootstrap Template</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/heroic-features.css" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">Start Bootstrap</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container">

    <!-- Jumbotron Header -->
    <header class="jumbotron my-4">
      <!-- <h1 class="display-3">A Warm Welcome!</h1>
      <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa, ipsam, eligendi, in quo sunt possimus non incidunt odit vero aliquid similique quaerat nam nobis illo aspernatur vitae fugiat numquam repellat.</p>
      <a href="#" class="btn btn-primary btn-lg">CART</a> -->
<?php

echo $cart->viewCartTableFull();
?>
 <a href="?clearCart=1" class="btn btn-primary btn-lg">Clear CART</a>
    </header>

    <!-- Page Features -->
    <div class="row text-center">

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/500x325" alt="">
          <div class="card-body">
            <h4 class="card-title">Product 1 biscuit</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente esse necessitatibus neque.</p>
          </div>
          <div class="card-footer">
            <a href="?add=1" class="btn btn-primary">Add to cart</a>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/500x325" alt="">
          <div class="card-body">
            <h4 class="card-title">Product 2 cream</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo magni sapiente, tempore debitis beatae culpa natus architecto.</p>
          </div>
          <div class="card-footer">
            <a href="?add=2" class="btn btn-primary">Add to cart</a>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/500x325" alt="">
          <div class="card-body">
            <h4 class="card-title">Product 3 	chocolate</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente esse necessitatibus neque.</p>
          </div>
          <div class="card-footer">
            <a href="?add=3" class="btn btn-primary">Add to cart</a>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/500x325" alt="">
          <div class="card-body">
            <h4 class="card-title">Product 4 book </h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo magni sapiente, tempore debitis beatae culpa natus architecto.</p>
          </div>
          <div class="card-footer">
            <a href="?add=4" class="btn btn-primary">Add to cart</a>
          </div>
        </div>
      </div>

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Your Website 2019</p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
