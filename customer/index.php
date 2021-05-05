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
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../font/bootstrap-icons.css">
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
                  <li class="nav-item">
                    <a href="#cartList" class="nav-link btn btn-no-border-orange"
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
                    <!--Navigation button to show the form to add item button--->
                 </li>
                 
                 <?php 
                   $cat_nav = getCategories($conn); 
                   if(!empty($cat_nav) || $cat_nav !== false){
                       foreach($cat_nav as $cat_key => $cat_val){?>
                           <li class="nav-item"><a href="#cat<?php echo $cat_val['cat_id'];?>" class="nav-link btn btn-link"> <?php echo $cat_val['cat_desc'];?> </a></li>
                       <?php }
                   }
                   ?>
                 
                 
                 <li class="nav-item">
                     <a href="../jquery_sample/index.php" class="nav-link btn btn-no-border-orange"> 
                        <i class="bi bi-app-indicator"></i> Jquery Sample
                     </a>                     
                 </li>
                 <li class="nav-item">
                     <a href="../includes/processlogout.php" class="nav-link btn btn-no-border-orange"> 
                        <i class="bi bi-power"></i> Logout
                     </a>                     
                 </li>
                </ul>
                <!--Search Bar-->
                <form action="index.php" method="GET" >
                 <div class="input-group">
                  <input id="searchbar" name="searchkey" type="text" class="form-control" placeholder="search">
                  <button class="btn btn-outline-primary"> Search <i class="bi bi-search"></i> </button>
                 </div>
                </form>
                <!--Search Bar-->
            </div>
             </div>
         </nav>
         <!--end Navigation Bar -->
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
                <div class="container-fluid">
                    <div class="row">
                    <h3 class="display-6">
                       <?php $summary = getCartSummary($conn, $_SESSION['user_id']);
                        foreach($summary as $key => $nval){
                           echo "(". $nval['total_qty'] . " pcs )";    
                        }
                        
                        ?> 
                       
                    </h3>
                     <?php
                      $sql_cart_list = "SELECT i.item_name
                                             , i.item_img
                                             , i.item_price
                                             , sum(c.item_qty) total_item_qty
                                             , sum(c.item_qty * i.item_price)  total_order_amt
                                          FROM cart c
                                          JOIN items i
                                            ON c.item_id = i.item_id
                                         WHERE c.user_id = ? 
                                            AND c.status = 'P'
                                            group by i.item_name
                                             , i.item_img
                                             , i.item_price; ";
                      $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        header("location: ?error=stmtfailed");
                        exit();
                    }
                        mysqli_stmt_bind_param($stmt, "s" ,$_SESSION['user_id']);
                        mysqli_stmt_execute($stmt);

                        $resultData = mysqli_stmt_get_result($stmt);
                    if(!empty($resultData)){
                        
                        while($row = mysqli_fetch_assoc($resultData)){ ?>
                            <div class="col-2">
                                <div class="card shadow">
                                    <img src="../images/<?php echo $row['item_img'] == '' ? "200x200.png" : $row['item_img']; ?>" alt="1 x 1" class="card-img-top" style=" height: 100px; width=100px; object-fit: cover">   
                                    <div class="card-body">
                                         <p class="card-title"><?php echo $row['item_name'] 
                                                           . "( Php " . number_format($row['item_price'],2)  
                                                           . ") x ". $row['total_item_qty']  ;?> 
                                    </p>
                                    </div>
                                    <div class="card-footer">
                                        <?php echo "Php" . number_format($row['total_order_amt'],2);  ?>
                                        
                                    </div>

                                </div>
                            </div>
                        <?php }
                    
                        ?>
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
                 <div class="row px-3">

<?php
    $category_list = getCategories($conn);
    if(!empty($category_list) || $category_list !== false){
        foreach($category_list as $categ_key => $cat){
        echo "<marker id='cat".$cat['cat_id']."' class='mt-5 mb-5'></marker>";
            
        echo "<br><br><h3 class='display-6'>".$cat['cat_desc']."</h3>";
             $menu = showMenu($conn, $cat['cat_id'], $searchkey);
             if(!empty($menu) || $menu !== false ){
                foreach($menu as $key => $val){ ?>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="card">
                       <img src="../images/<?php echo $val['item_img'] == '' ? "200x200.png" : $val['item_img']; ?>" alt="1 x 1" class="card-img-top" style=" height: 300px; width=300px; object-fit: cover">   
                       
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $val['item_name']?></h5>
                            <p class="card-text"><?php echo $val['item_short_code']; ?></p>
                            <em class="card-text" > Php <?php echo number_format($val['item_price'],2); ?> </em>
                        </div>
                        <div class="card-footer">
                            <form action="../includes/processorder.php" method="get">
                                <input hidden type="text" name="item_id" value="<?php echo $val['item_id']; ?>" >
                                <div class="container">
                                   <div class="row">
                                       <div class="col-lg-3"></div>
                                       <div class="col-lg-6">
                                            <div class="input-group">
                                               <input class="form-control" type="number" name="item_qty" value="1" >    
                                               <button type="submit" class="btn btn-primary"> <i class="bi bi-cart-plus"></i> </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-3"></div>
                                   </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php }
             }
             else{
                 echo "<h4> No Records Found.</h4>";
             }   
        }
    }
?>
        </div>
        
    </div>
</div>
     
</body>
<?php mysqli_close($conn);?>
<script src="../js/bootstrap.min.js"></script>
</html>
