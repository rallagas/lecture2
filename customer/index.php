<?php
session_start();
if(isset($_SESSION['user_type']) && isset($_SESSION['user_id'])){
    if($_SESSION['user_type'] == 'A'){
        header("location: ../admin/?error=cannotgothere");
    }
}
else{
    header("location: ../?error=cannotgothere");
}

include_once "../includes/db_conn.php";
include_once "../includes/func.inc.php";
$page='index';
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
</head>

<body>

    <div class="container-fluid">
        <div class="row pt-5" id="NavigationPanel">
            <?php include_once "cust_nav.php"; ?>

        </div>

        <div class="row" id="MenuList">
            <div class="col-2 pt-5 shadow collapse show bg-warning" id="cartList">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <p class="lead text-dark">
                                <?php $summary = getCartSummary($conn, $_SESSION['user_id']); 
                                    if(!empty($summary)){ ?>
                                <a href="checkout.php" class="btn btn-outline-light border-3 text-danger fs-4 mt-3"> <i class="bi bi-cash"></i> Checkout </a>
                                <br>
                                <?php foreach($summary as $key => $nval){
                                           echo "Total Qty: ". $nval['total_qty'] . " pcs |";  
                                           echo "Total Price: Php ". number_format($nval['total_price'],2);    
                                        }
                                    }
                                    ?>
                            </p>

                        </div>
                        <?php
                      $cart_list = getCartList($conn, $_SESSION['user_id']);
                      if(!empty($cart_list) || $cart_list !== false){
                          foreach($cart_list as $cart_key => $cart) { ?>
                        <div class="col-12 mb-4">
                            <div class="card shadow">
                                <img src="../images/<?php echo $cart['item_img'] == '' ? "200x200.png" : $cart['item_img']; ?>" alt="1 x 1" class="card-img-top" style=" height: 150px; width=100px; object-fit: cover">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo $cart['item_name'] ;?> </h6>
                                    <span class='fs-6'><?php
                                                         echo "Php " . number_format($cart['item_price'],2)  
                                                                     . " x ". $cart['total_item_qty'] . ($cart['total_item_qty'] > 1 ? ' pcs' : ' pc') ;
                                                         ?>
                                        <?php echo "<br>  Total : Php " . number_format($cart['total_order_amt'],2);  ?>
                                    </span>
                                    <a href="?deletecartitem=<?php echo $cart['item_id']; ?>" class="position-absolute top-0 start-100 translate-middle bg-light btn-outline-danger" title="Remove from Cart"><i class="bi bi-x"></i></a>
                                </div>
                                <div class="card-footer">

                                    <?php 
                                       if($cart['confirm'] == 'X'){ ?>
                                    <a href="?confirmcartitem=<?php echo $cart['item_id']; ?>" class="float-end btn btn-sm btn-outline-light text-dark"> <i class="bi bi-app"></i> </a>
                                    <?php }
                                            else { ?>
                                    <a href="?unconfirmcartitem=<?php echo $cart['item_id']; ?>" class="float-end btn btn-sm btn-outline-light text-dark"> <i class="bi bi-check-square"></i> </a>
                                    <?php }
                                        ?>

                                </div>

                            </div>
                        </div>
                        <?php }
                                } ?>
                    </div>
                </div>
            </div>
            <div class="col-10">
                <div class="container-fluid">
                    <?php
$category_list = getCategories($conn);
if(!isset($searchkey)){
    if(!empty($category_list) || $category_list !== false){
        foreach($category_list as $categ_key => $cat){ ?>
                    <div class="row px-3 mb-3">
                        <?php echo "<marker id='cat".$cat['cat_id']."' class='mt-5 mb-5'></marker>"; ?>

                        <div class="col-12">
                            <img src="../images/<?php echo $cat['cat_icon']; ?>" alt="1x1" class="d-inline mx-3 rounded-circle float-start img-fluid" width="50px">
                            <h3 class='display-6 d-inline'> <?php echo $cat['cat_desc']; ?></h3>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="container-fluid">
                                <div class="row">

                                    <?php
             $menu = showMenu($conn, $cat['cat_id']);
             if(!empty($menu) || $menu !== false ){
                foreach($menu as $key => $val){ ?>
                                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">

                                        <div class="card">
                                            <img src="../images/<?php echo $val['item_img'] == '' ? "200x200.png" : $val['item_img']; ?>" alt="1 x 1" class="card-img-top" style=" height: 300px; width=300px; object-fit: cover">

                                            <div class="card-body">
                                                <form action="../includes/processorder.php" method="get">
                                                    <input type="hidden" name="item_id" value="<?php echo $val['item_id']; ?>">
                                                    <div class="input-group">
                                                        <label class="input-group-text" for="qty_<?php echo $val['item_id']; ?>">Qty:</label>
                                                        <input class="form-control form-control-sm" type="number" name="item_qty" id="qty_<?php echo $val['item_id']; ?>" value="<?php echo $val['minimum_qty']; ?>">
                                                    </div>
                                                    <button type="submit" class="border-4  btn btn-lg btn-outline-light position-absolute top-50 start-50 translate-middle"> <i class="bi bi-cart-plus"></i> </button>
                                                </form>
                                                <h5 class="card-title"><?php echo $val['item_name']?></h5>
                                                <em class="card-text"> Php <?php echo number_format($val['item_price'],2); ?> </em>
                                            </div>
                                        </div>
                                    </div>

                                    <?php }
             }
             else{
                 echo "<h4> No Records Found.</h4>";
             }   ?>
                                </div>
                            </div>
                        </div>
                        <?php }
    }
} else{  ?>
                        <div class="col-12" id="resultSetSearch">
                            <?php
            echo "<p class='lead'>Result for {$searchkey}:</p><hr>";
             $menu = showMenu($conn, null, $searchkey);
             if(!empty($menu) || $menu !== false ){
                foreach($menu as $key => $val){ ?>
                            <div class="col-lg-2 col-md-6 col-sm-6">

                                <div class="card">
                                    <img src="../images/<?php echo $val['item_img'] == '' ? "200x200.png" : $val['item_img']; ?>" alt="1 x 1" class="card-img-top" style=" height: 300px; width=300px; object-fit: cover">

                                    <div class="card-body">
                                        <form action="../includes/processorder.php" method="get">
                                            <input type="hidden" name="item_id" value="<?php echo $val['item_id']; ?>">
                                            <div class="input-group">
                                                <label class="input-group-text" for="qty_<?php echo $val['item_id']; ?>">Qty:</label>
                                                <input class="form-control form-control-sm" type="number" name="item_qty" id="qty_<?php echo $val['item_id']; ?>" value="1">
                                            </div>
                                            <button type="submit" class="border-4 btn btn-lg btn-outline-light position-absolute top-50 start-50 translate-middle "> <i class="bi bi-cart-plus"></i> </button>
                                        </form>
                                        <h5 class="card-title"><?php echo $val['item_name']?></h5>
                                        <em class="card-text"> Php <?php echo number_format($val['item_price'],2); ?> </em>
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

        <div class="row footer">
            <p class="text-end fw-light text-reset fixed-bottom float-end me-1">
                All Rights Reserved 2021 &copy; rallagas
            </p>
        </div>
    </div>
</body>
<?php mysqli_close($conn);?>
<script src="../js/bootstrap.min.js"></script>

</html>
