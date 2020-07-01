<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
require_once '../vendor/autoload.php';

session_start();

use \Stnc\ShoppingCart\HtmlHelper;


$cart_name = 'stnc'; // sepetin session değerine bir değer atadık

$cart= new HtmlHelper($cart_name);
$cart->groups=true;

/* other call
$cart_other = new  \stnc\cart\Cart('stnc', 'ds');
print_r($cart_other);
*/
if (isset($_GET['add']) && $_GET['add']==1  ){
$data = array(
    'productID' => 1,
    'productName' => "biscuit",
    'productImageURL' => "http://placehold.it/500x325",
    'URL' => "https://example.org/product/1",
    'price' => 80,
    "totalEach" => 1,
    'stockUnit'=>'unit',
    "totalPrice" => 80
);
$cart->addToCart(1, $data);
}

if (isset($_GET['add']) && $_GET['add']==2  ){
  $data = array(
    'productID' => 2,
    'productName' => "cream",
    'productImageURL' => "http://placehold.it/500x325",
    'URL' => "https://example.org/product/2",
    'price' => 45,
    "totalEach" => 1,
    'stockUnit'=>'unit',
    "totalPrice" => 45
  );
  $cart->addToCart(2, $data);
  }


  if (isset($_GET['add']) && $_GET['add']==3  ){
    $data = array(
        'productID' => 3,
        'productName' => "chocolate",
        'productImageURL' => "http://placehold.it/500x325",
        'URL' => "https://example.org/product/4",
        'price' => 15,
        "totalEach" => 1,
        'stockUnit'=>'unit',
        "totalPrice" => 15
    );
    $cart->addToCart(3, $data);
    }

    if (isset($_GET['add']) && $_GET['add']==4  ){
      $data = array(
        'productID' => 4,
        'productName' => "book",
        'productImageURL' => "http://placehold.it/500x325",
        'URL' => "https://example.org/product/4",
        'price' => 23.3,
        "totalEach" => 1,
        'stockUnit'=>'unit',
        "totalPrice" => 23.3,
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


if (isset($_GET['emptyCart']) && $_GET['emptyCart']==1  ){
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
 <a href="?emptyCart=1" class="btn btn-primary btn-lg">Clear CART</a>
    </header>

    <!-- Page Features -->
    <div class="row text-center">

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" src="http://placehold.it/500x325" alt="">
          <div class="card-body">
            <h4 class="card-title"> biscuit</h4>
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
            <h4 class="card-title"> cream</h4>
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
            <h4 class="card-title"> 	chocolate</h4>
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
            <h4 class="card-title"> book </h4>
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
