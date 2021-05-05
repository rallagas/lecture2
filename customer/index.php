<?php
session_start();
include_once "../includes/db_conn.php";
include_once "../includes/func.inc.php";
$searchkey=NULL;
if (isset($_GET['searchkey'])){
    $searchkey=htmlentities($_GET['searchkey']);  
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lecture : SQL Integration with PHP</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/custom.css">
    <style>
    body{
    background-image: url('../images/bg-theme-2.jpg');
    background-repeat: no-repeat;
     background-attachment: fixed;
}
    </style>
</head>

<body>
       
<div class="container-fluid">
    <div class="row pt-5" id="NavigationPanel">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg bg-light text-white shadow fixed-top">
            <div class="container-fluid">
             <a href="index.php" class="navbar-brand btn btn-no-border-orange pb-3"> 
                <i class="bi bi-house"></i> 
                </a>
            <button class="navbar-toggler btn btn-outline-orange" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="bi bi-list"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                  
                 
                 <?php 
                   $cat_nav = getCategories($conn); 
                   if(!empty($cat_nav) || $cat_nav !== false){
                       foreach($cat_nav as $cat_key => $cat_val){?>
                           <li class="nav-item">
                             <a href="#cat<?php echo $cat_val['cat_id'];?>" class="nav-link btn btn-link"> <?php echo $cat_val['cat_desc'];?> </a>
                           </li>
                       <?php }
                   }
                   ?>
                 
                 
                    <!--Navigation button to show the form to add item button--->
               
                </ul>
                <!--Search Bar-->
                   <a href="#userprofile" class="nav-link btn float-end"
                           data-bs-toggle="collapse" 
                               role="button"  
                               aria-expanded="false"  
                               aria-controls="userprofile"
                    >
                    <i class="bi bi-person"></i> <?php echo getUserFullName($conn,$_SESSION['user_id']); ?>  
                    </a>
                  <a href="../includes/processlogout.php" class="nav-link btn float-end"> 
                        <i class="bi bi-power"></i> Logout
                  </a>  
                 
                  <a href="#cartList" class="nav-link btn float-end"
                           data-bs-toggle="collapse" 
                               role="button"  
                               aria-expanded="false"  
                               aria-controls="cartList"
                    >
                    <i class="bi bi-cart"></i> Cart  
                        <span class="badge bg-danger">
                           <?php echo getCartCount($conn,$_SESSION['user_id']);?>
                        </span>
                        
                    </a>
                     <?php
                     if(isset($_GET['deletecartitem'])){
                         if(deleteCartItem($conn,htmlentities($_GET['deletecartitem']),$_SESSION['user_id']) !== false){ ?>
                             <div class="badge bg-warning">Cart Item Removed.</div>
                         <?php }
                     }
                     ?> 
                     <a href="../jquery_sample/index.php" class="inline nav-link btn btn-no-border-orange float-end"> 
                        <i class="bi bi-app-indicator"></i>
                     </a>                     
                <form action="index.php" method="GET" >
                 <div class="input-group inline">
                  <input id="searchbar" name="searchkey" type="text" class="form-control" placeholder="search">
                  <button class="btn btn-outline-primary"> Search <i class="bi bi-search"></i> </button>
                 </div>
                </form>
                <!--Search Bar-->
            </div>
            
             </div>
            <br>
            
            
         </nav>
         <!--end Navigation Bar -->
         <br>
         
    </div>
    
    <div class="row mx-3 mt-3" id="ProcessesPanel">
       
        <div class="col-12">
            <?php if(isset($_GET['error'])){
                    
                    switch($_GET['error']){
                        case 1: 
                            if(isset($_GET['itemname'])){
                               echo "<p class='text-danger'>".$_GET['itemname']." Exists.</p>";
                            }
                                break;
                        case 2: echo "<p class='text-danger'>Adding Record Failed.</p>";
                                break;
                        case 3: echo "<p class='text-danger'>Checking Item Failed.</p>";
                                break;
                        case 0:
                            if(isset($_GET['itemname'])){
                               echo "<p class='text-success'>".$_GET['itemname']." has been added.</p>";
                            }
                                break;
                        default: echo "";
                    }
                  } ?>
           
            <div id="cartList" class="collapse mt-3 py-3">
                <div class="container">
                    <div class="row">
                    <h3 class="display-6">
                       <?php $summary = getCartSummary($conn, $_SESSION['user_id']);
                        foreach($summary as $key => $nval){
                           echo "<i class='bi bi-cart'></i> Cart (". $nval['total_qty'] . " pcs )";    
                        }
                        
                        ?> 
                       
                    </h3>
                     <?php
                    $cart_list = getCartList($conn, $_SESSION['user_id']);
                    if(!empty($cart_list) || $cart_list !== false){
                        foreach($cart_list as $cart_key => $cart) { ?>
                            <div class="col-2">
                                <div class="card shadow">
                                   
                                      <img src="../images/<?php echo $cart['item_img'] == '' ? "200x200.png" : $cart['item_img']; ?>" alt="1 x 1" class="card-img-top" style=" height: 150px; width=100px; object-fit: cover">   
                                   
                                    <div class="card-body">   
                                            <h3 class="card-title"><?php echo $cart['item_name'] ;?>  </h3>                                      
                                         <p class='lead'><?php
                                             echo "Php " . number_format($cart['item_price'],2)  
                                                           . " <br> Qty: ". $cart['total_item_qty']  
                                             ?></p>
                                    </div>
                                    <div class="card-footer">
                                        
                                        <a href="?deletecartitem=<?php echo $cart['item_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-x"></i></a>
                                        <?php echo "Total : Php " . number_format($cart['total_order_amt'],2);  ?>                                        
                                    </div>

                                </div>
                            </div>
                        <?php }
                    } ?>
                        <hr>
                    <p class="lead">
                    <?php $summary = getCartSummary($conn, $_SESSION['user_id']); 
                    if(!empty($summary)){
                        foreach($summary as $key => $nval){
                           echo "Total Qty: ". $nval['total_qty'] . " pcs |";  
                           echo "Total Price: Php ". number_format($nval['total_price'],2);    
                        }
                    echo "<br>If you need more. you can always order more below :)";
                    }
                    else{
                     echo "You have to order something from below";   
                    } 
                    ?>
                    </p>
                    </div>
                </div>
            </div>
           
        </div>
    
    </div>
<div class="row" id="MenuList">
        <div class="col-12">
         <div class="container-fluid">
             
<?php
$category_list = getCategories($conn);
if(!isset($searchkey)){
    if(!empty($category_list) || $category_list !== false){
        foreach($category_list as $categ_key => $cat){
        echo "<div class='row px-3 mb-3'>";
            
        echo "<marker id='cat".$cat['cat_id']."' class='mt-5 mb-5'></marker>"; ?>
           
           <div class="col-12">
              <div class="clear-fix">
                 <img src="../images/<?php echo $cat['cat_icon']; ?>" alt="1x1" class="d-inline mx-3 rounded-circle float-start img-fluid" width="50px">   
                 <h3 class='display-6 d-inline'>  <?php echo $cat['cat_desc']; ?></h3>
              </div>
               
            
                 </div>
                <div class="row mt-3">
            <?php
             $menu = showMenu($conn, $cat['cat_id']);
             if(!empty($menu) || $menu !== false ){
                foreach($menu as $key => $val){ ?>
                <div class="col-lg-2 col-md-6 col-sm-6">
                   
                    <div class="card">
                       <img src="../images/<?php echo $val['item_img'] == '' ? "200x200.png" : $val['item_img']; ?>" alt="1 x 1" class="card-img-top" style=" height: 300px; width=300px; object-fit: cover">   
                       <form action="../includes/processorder.php" method="get">
                                <input type="hidden" name="item_id" value="<?php echo $val['item_id']; ?>" >
                                <input type="hidden" name="item_qty" value="1" >    
                                <button type="submit" class="btn btn-lg btn-outline-light position-absolute top-50 start-50 translate-middle "> <i class="bi bi-cart-plus"></i> </button>
                            </form> 
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $val['item_name']?></h5>
                            <p class="card-text"><?php echo $val['item_short_code']; ?></p>
                            <em class="card-text" > Php <?php echo number_format($val['item_price'],2); ?> </em>
                        </div>
                    </div>
                </div>

                <?php }
             }
             else{
                 echo "<h4> No Records Found.</h4>";
             }   ?>
                 </div>
        <?php }
    }
} else{  ?>
         <div class="row mt-3" id="resultSetSearch">
    <?php
            echo "<p class='lead'>Result for {$searchkey}:</p><hr>";
             $menu = showMenu($conn, null, $searchkey);
             if(!empty($menu) || $menu !== false ){
                foreach($menu as $key => $val){ ?>
                <div class="col-lg-2 col-md-6 col-sm-6">
                   
                    <div class="card">
                       <img src="../images/<?php echo $val['item_img'] == '' ? "200x200.png" : $val['item_img']; ?>" alt="1 x 1" class="card-img-top" style=" height: 300px; width=300px; object-fit: cover">   
                       <form action="../includes/processorder.php" method="get">
                                <input type="hidden" name="item_id" value="<?php echo $val['item_id']; ?>" >
                                <input type="hidden" name="item_qty" value="1" >    
                                <button type="submit" class="btn btn-lg btn-outline-light position-absolute top-50 start-50 translate-middle "> <i class="bi bi-cart-plus"></i> </button>
                            </form> 
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $val['item_name']?></h5>
                            <p class="card-text"><?php echo $val['item_short_code']; ?></p>
                            <em class="card-text" > Php <?php echo number_format($val['item_price'],2); ?> </em>
                        </div>
                    </div>
                </div>

                <?php }
             }
             else{
                 echo "<h4> No Records Found.</h4>";
             }   ?>
        </div>
<?php } ?>
        
    </div>
</div>
</div>
</div>     
<div class="footer">
    <p class="text-end fw-light text-reset fixed-bottom float-end me-1">
        All Rights Reserved 2021 &copy; rallagas
        
    </p>
</div>
</body>
<?php mysqli_close($conn);?>
<script src="../js/bootstrap.min.js"></script>
</html>
