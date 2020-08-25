<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
require_once 'vendor/autoload.php';

session_start();

use \Stnc\ShoppingCart\Cart;


$cart_name = 'stnc'; // sepetin session değerine bir değer atadık

$cart= new Cart($cart_name);
$cart->groups=true;

$cartItems=$cart->viewCartArray();

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
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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




    <?php if (!empty($cartItems)): ?>
		<div class=" bg-light">
			<div class="container py-3">
				<div class="row ">
					<div class="col-12 mx-auto">

							<hr>
							<h2 style="margin:20px 0">CART</h2>
							<hr>
						
              <div class="bg-white text-dark col-12 mx-auto">
              <div class="row">
								  <table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Product Name</th>
											<th>Price</th>
											<th>Piece</th>
											<th>totalPrice</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($cartItems as $key => $item): ?>
											<tr>
												<td>
													<a href="
                            <?php echo $item['URL'] ?>">
														<?php echo $item['productName'] ?>
													</a>
												</td>
												<td>
													<?php echo $item['price'] ?> ₺ </td>
												<td>
													<?php echo $item['totalEach'] ?> Piece </td>
												<td>
													<?php echo $item['totalPrice'] ?> ₺ </td>
												<td>
													<a href="/cart.php?clear=<?php echo $item['productID'] ?>"> Delete </a>
												</td>
											</tr>
											<?php endforeach ?>
									</tbody>
								</table>
                </div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="tabloToplamtutar"> <strong> Total Price : </strong><?php echo $cart->subTotal ?> ₺</div>
								</div>
							</div>
              <a href="/cart.php?emptyCart=1" class="btn btn-primary btn-lg">Clear CART</a>
					</div>
          <?php
else: ?>
							<hr>
             <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-primary" style="font-size:25px; text-align:center;" role="alert"> Cart Empty
                        <a class="btn btn-primary btn-block" href="/index.php">Back to homepage</a>
                        </div>
                    </div>
                </div>
            </div>
         <?php
endif; ?>



  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Your Website 2019</p>
    </div>
    <!-- /.container -->
  </footer>



</body>

</html>
