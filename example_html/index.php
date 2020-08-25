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
   /* other call
   $cart_other = new  \stnc\cart\Cart('stnc', 'ds');
   print_r($cart_other);
   */

   
   
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


     $class="";
     $classTotal="";
     if (empty($items)){
       $class='style="
       text-align: center;
       margin: 10px;"';
     
       $classTotal='style="display:none"';
     }

     stncDeleteToCartAjax($cart);
     function stncDeleteToCartAjax($cart)
     {
     

     
         if (isset($_GET['action']) && $_GET['action'] == 'ch_ajax_delete_to_cart') {
     
          
  
        
              if (isset($_GET['id'])){
                $cart->removeCart($_GET['id']);
              }
              
          
   

             $items = $cart->viewCartArray();
             //echo $cart->getJSON();
             echo sepetEvents($items, $cart->subTotal);
             die();
         }
     }



     stncAddToCartAjax($cart);
     function stncAddToCartAjax($cart)
     {
     
         if (isset($_GET['action']) && $_GET['action'] == 'ch_ajax_add_to_cart') {
     
             $id = $_GET['id'];
             if (isset($_GET['id']) && $_GET['id']==1  ){
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
              
              if (isset($_GET['id']) && $_GET['id']==2  ){
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
              
              
                if (isset($_GET['id']) && $_GET['id']==3  ){
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
              
                  if (isset($_GET['id']) && $_GET['id']==4  ){
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
     
             $items = $cart->viewCartArray();
             //echo $cart->getJSON();
             echo sepetEvents($items, $cart->subTotal);
     
             die();
         }
     }

//    $items = $this->viewCartArray();
//bu kendisinden gelen metodla basacak html 
function sepetEvents($items, $subTotal)
{
    $class = "";
    $classTotal = "";
    if (empty($items)) {
        $class = 'style="
        text-align: center;
        margin: 10px;"';

        $classTotal = 'style="display:none"';
    }
    $products = '<div class="sepetWrap"' . $class . '><div class="sepetItems">';

    if (!empty($items)) {
        foreach ($items as $items_key => $item) {
            $products .= '<div class="sepetItem">
    <div class="itemSil">
        <button type="button" data-value="' . $items_key . '"
                class="btn action-button siparissil" >
            <i class="fa fa-times-circle" aria-hidden="true"></i>
        </button>
    </div>
    <div class="info">

        <div class="urun">
           ' . $item['productName'] . '
        </div>

        <div class="musteri"></div>
    </div>

    <div class="sepetFiyat">
        <div class="sptfiyat"> ' . $item['price'] . ' $</div>
        <div class="sptadet"> ' . $item['totalEach'] . ' Piece </div>
    </div>

</div>';

        }
    } else {
        $products .= '<strong>Cart Empty</strong>';
    }
    $products .= '
</div>
<div class="cizgi"></div>
</div>
<div class="sepetBottom" ' . $classTotal . ' >
<div class="toplam">Total</div>
<div class="fiyat">' . $subTotal . ' ₺	</div>
';
    return $products;
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
                     <a class="nav-link" href="index.php">Home
                     <span class="sr-only">(current)</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#">About</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#">Json Example</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="cart.php">CART</a>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
      <!-- Page Content -->
      <div class="container">
         <!-- Jumbotron Header -->
         <header class="jumbotron my-4">
            <h1 class="text-center">MY AVM SHOP</h1>
         </header>
         <div class="row">
         <div class="col-lg-9 col-md-10  align-items-stretch">
            <div class="row text-center">
               <div class="col-lg-3 col-md-6 mb-4">
                  <div class="card h-100">
                     <img class="card-img-top" src="http://placehold.it/500x325" alt="">
                     <div class="card-body">
                        <h4 class="card-title"> biscuit</h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente esse necessitatibus neque.</p>
                     </div>
                     <div class="card-footer">
                        <a href="?add=1" data-value="1" class="btn btn-primary siparisver">Add to cart</a>
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
                        <a href="?add=2" data-value="2" class="btn btn-primary siparisver">Add to cart</a>
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
                        <a href="?add=3" data-value="3" class="btn btn-primary siparisver">Add to cart</a>
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
                        <a href="?add=4" data-value="4" class="btn btn-primary siparisver">Add to cart</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- /.row -->
         <div class="col-lg-3 col-md-2 d-flex align-items-stretch">
			<div class="row">
				<div class="sepet">
							<h2 id="sepetBaslik">Quick cart </h2>

							<div class="display" id="sepettumu">
								


									<div class="sepetWrap" <?php echo $class?> >

										<div class="sepetItems">
											<?php 
											if (!empty($cartItems)): 
											foreach ($cartItems as $items_key => $item) :?>
											<div class="sepetItem">
												<div class="itemSil">
													<button type="button" data-value="<?php echo $items_key ?>"  
															class="btn action-button siparissil" >
														<i class="fa fa-times-circle" aria-hidden="true"></i>
													</button>
												</div>
												<div class="info">

													<div class="urun">
														<?php echo $item['productName'] ?>
													</div>

													<div class="musteri"></div>
												</div>
								


												<div class="sepetFiyat">
													<div class="sptfiyat"> <?php echo $item['price'] ?> ₺	</div>
													<div class="sptadet"> <?php echo $item['totalEach'] ?> Piece </div>
												</div>


											</div>
										
											<?php endforeach ?>
											<?php else: ?>
											<strong>Cart Empty</strong>

											<?php endif; ?>

										</div>
										<div class="cizgi"></div>
									</div>



									<div class="sepetBottom" <?php echo $classTotal?>>
									<div class="toplam">Total</div>
									<div class="fiyat"><?php echo $cart->subTotal ?> ₺	</div>

							</div>

						
							</div>

							<div style="text-align:center; margin: 10px 0;">
								<a class="btn btn-primary"  href="/cart.php">go to cart </a>
							</div>

				</div>
			</div>
		</div>
         
         </div>

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
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
  <script>
  jQuery(document).on("click touchstart", "a.siparisver", function (event) {

event.preventDefault();
var myID = jQuery(this).data('value');


    jQuery.ajax({
        type: 'GET',

        datatype: 'json',
        url: "index.php",
        data: {action: "ch_ajax_add_to_cart", "id": myID},
        beforeSend: function () {
            //loadMore.text('').addClass('active');
        },
        success: function (datas) {
            jQuery("#sepettumu").empty();
            jQuery("#sepettumu").html(datas);

        },
        error: function (e) {
            console.log(e);
        },
        complete: function () {            
        },
    });
    return false; // for good measure

});


jQuery(document).on("click touchstart", "button.siparissil", function (event) {

event.preventDefault();
var myID = jQuery(this).data('value');
jQuery.ajax({
    type: 'GET',

    datatype: 'json',
    url: "index.php",
    data: {action: "ch_ajax_delete_to_cart", "id": myID},

    beforeSend: function () {
        //loadMore.text('').addClass('active');
    },
    success: function (datas) {
        jQuery("#sepettumu ").empty();
        jQuery("#sepettumu").html(datas);
    },
    error: function (e) {
        console.log(e);
    },
    complete: function () {


    },
});
return false; // for good measure
});
  </script>
  
   </body>
</html>