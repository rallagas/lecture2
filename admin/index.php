<?php
session_start();

include_once "../includes/db_conn.php";
include_once "../includes/func.inc.php";
$searchkey="";
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
</head>
<body>
       
<div class="container-fluid">
    <div class="row" id="NavigationPanel">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg bg-light text-white shadow-sm">
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
                    <!--Navigation button to show the form to add item button-->
                    <a class="nav-link btn btn-no-border-orange"  data-bs-toggle="collapse"  
                         href="#addItemForm"  role="button"  aria-expanded="false" aria-controls="addItemForm">
                         Add Item  <i class="bi bi-plus-circle"></i>
                    </a> 
                  </li>
                  <li class="nav-item">
                    <a href="#cartList" class="nav-link btn btn-no-border-orange"
                           data-bs-toggle="collapse" 
                               role="button"  
                               aria-expanded="false"  
                               aria-controls="cartList"
                    >
                        <i class="bi bi-cart"></i> Cart  
                        <?php 
                        $sql_cart_count = "SELECT COUNT(*) cartcount FROM `cart` WHERE status = 'P' AND user_id = ?;";
                        $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_count)){
                        header("location: ?error=stmtfailed");
                        exit();
                    }
                        mysqli_stmt_bind_param($stmt, "s" ,$_SESSION['userid']);
                        mysqli_stmt_execute($stmt);

                        $resultData = mysqli_stmt_get_result($stmt);

                        if($row = mysqli_fetch_assoc($resultData)){ ?>
                            <span class="badge bg-danger"><?php echo $row['cartcount']; ?></span>
                        <?php }
                       
                        ?>
                        
                    </a>
                    <!--Navigation button to show the form to add item button--->
                 </li>
                 <li class="nav-item"></li>
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
    
    <div class="row mx-3">
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
           
            <div id="addItemForm" class="card collapse mt-3 shadow">
               <div class="card-header">
                   <h3 class="display-6">Add New Item</h3>
               </div>
             <form action="additem.php" method="POST">
               <div class="card-body">
                  <div class="mb-1">
                      <label for="i_ItemName" class="form-label">Item Name</label>
                      <input name="itemname" id="i_ItemName" type="text" class="form-control">
                  </div>
                  <div class="mb-1">
                      <label for="" class="form-label">Item Short Code</label>
                      <input name="itemshortcode" type="text" class="form-control">
                  </div>
                  <div class="mb-1">
                      <label for="" class="form-label">Item Price</label>
                      <input name="itemprice" type="Number" class="form-control">
                  </div>
                  <div class="mb-1">
                   <label for="SelectCategory" class="form-label">Category</label>
                   <select name="itemcategory" id="" class="form-select">
                    <?php
                      $sql_cat = "SELECT cat_id, cat_desc FROM category WHERE cat_status = 'A';";
                      $result = mysqli_query($conn, $sql_cat);
                      if(mysqli_num_rows($result) > 0){
                          while($row = mysqli_fetch_assoc($result)){
                              echo "<option value='".$row['cat_id']."'>".$row['cat_desc']."</option>";
                          }
                      }
                    ?>
                   </select>                   
                  </div>
                  <div class="mb-1">
                     <label for="" class="form-label">Status</label>
                      <select name="itemstatus" id="" class="form-select">
                          <option value="A">Active</option>
                          <option value="D">Discontinued</option>
                      </select>
                  </div>
               </div>
               <div class="card-footer">
                   <button class="btn btn-outline-primary"> <i class="bi bi-save"></i> Save </button>
               </div>
             </form>
            </div>
            
            
            <div id="cartList" class="collapse mt-3 py-3">
                <div class="container">
                    <div class="row">
                    <h3 class="display-4">Cart Items 
                       <?php $summary = getCartSummary($conn, $_SESSION['userid']); 
                        foreach($summary as $key => $nval){
                           echo "(". $nval['total_qty'] . " pcs )";    
                        }
                        
                        ?> 
                       
                    </h3>
                     <?php
                      $sql_cart_list = "SELECT c.cart_id
                                             , i.item_name
                                             , i.item_img
                                             , i.item_price
                                             , c.item_qty
                                             , c.user_id
                                          FROM cart c
                                          JOIN items i
                                            ON c.item_id = i.item_id
                                         WHERE c.user_id = ? 
                                            AND c.status = 'P'; ";
                      $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        header("location: ?error=stmtfailed");
                        exit();
                    }
                        mysqli_stmt_bind_param($stmt, "s" ,$_SESSION['userid']);
                        mysqli_stmt_execute($stmt);

                        $resultData = mysqli_stmt_get_result($stmt);

                        while($row = mysqli_fetch_assoc($resultData)){ ?>
                            <div class="col-2">
                                <div class="card shadow">
                                    <img src="../images/<?php echo $row['item_img'];?>" alt="" class="card-img-top">
                                    <div class="card-header">
                                         <p class="card-title"><?php echo $row['item_name'] 
                                                           . "( Php " . number_format($row['item_price'],2)  . ")"  ;?> 
                                    </p>
                                    </div>
                                    <div class="card-footer">
                                        <form action="includes/updatecart.php" method="post">
                                           <input hidden type="text" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm" name="item_qty" value="<?php echo $row['item_qty']; ?>">
                                                <button class="btn btn-primary"> <i class="bi bi-bag-check"></i> </button>
                                                <a href="includes/deletecartitem.php?cartid=<?php echo $row['cart_id']; ?>" class="btn btn-danger">
                                                     <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </form>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php }
                       
                        ?>
                        <hr>
                    <p class="">
                    <?php $summary = getCartSummary($conn, $_SESSION['userid']); 
                        foreach($summary as $key => $nval){
                           echo "Total Qty: ". $nval['total_qty'] . " pcs |";  
                           echo "Total Price: Php ". number_format($nval['total_price'],2);    
                        }
                        
                        ?> 
                    </p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="row">
        <div class="col-12">
        <h3 class="display-3">Menu</h3>
        <?php
            //check if searchkey has no value
        if($searchkey == "") {
            //declare the SQL
           $sql = "SELECT i.item_id
                     , i.item_name
                     , i.item_short_code
                     , c.cat_desc
                     , i.item_price
                     , i.item_img
                  FROM `items` i
                  JOIN `category` c
                    ON i.cat_id = c.cat_id ;";
        
            //initialize connection to the database.
        $stmt=mysqli_stmt_init($conn);
            //prepare the statement
         if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "Statement Failed.";
        exit();
         }
        }
            //check if searchkey has value
        else{
            //initialize SQL
            $sql = "SELECT i.item_id
                     , i.item_name
                     , i.item_short_code
                     , c.cat_desc
                     , i.item_price
                     , i.item_img
                  FROM `items` i
                  JOIN `category` c
                    ON i.cat_id = c.cat_id
                 WHERE i.item_name LIKE ?
                    OR i.item_short_code = ?
                    OR c.cat_desc = ?
                    OR i.item_price = ?;";
        //initialize connection
        $stmt=mysqli_stmt_init($conn);
        //prepare statement
         if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "Something went wrong.";
            exit();
         }
        //initialize search key with Wild Card for itemnames
        $itemname="%{$searchkey}%";
        //bind the statement with the string in the search key. since we can declare a variable
        // multiple times, we can re-instate binding of a statement from each of the WHERE clause "?"
        // from the declared SQL variable string.
        mysqli_stmt_bind_param($stmt, "ssss" , $itemname, $searchkey, $searchkey, $searchkey);
        }
        //finally, whichever the situation, it will execute the statement 
         mysqli_stmt_execute($stmt);
         
        //get the results of the executed statement and put it into a variable
         $resultData = mysqli_stmt_get_result($stmt);
        //declare a container array.
         $arr=array();
         while($row = mysqli_fetch_assoc($resultData)){
             //we will do the transfer of data to another array to test if there is a result.
             array_push($arr,$row);
         }
         // if the new array is not empty, display the tabular representation
         // as HTML
         if(!empty($arr)){
            
             ?>
             <div class="container-fluid">
                 <div class="row px-3">
                 
             <?php
            foreach($arr as $key => $val){ ?>
            <div class="col-3">
                <div class="card">
                   <img src="images/<?php echo $val['item_img']; ?>" alt="1 x 1" width="100px" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $val['item_name']?></h5>
                        <p class="card-text"><?php echo $val['item_short_code']; ?></p>
                        <em class="card-text" > Php <?php echo number_format($val['item_price'],2); ?> </em>
                    </div>
                    <div class="card-footer">
                        <form action="includes/processorder.php" method="get">
                            <input hidden type="text" name="item_id" value="<?php echo $val['item_id']; ?>" >
                            <div class="input-group">
                               <input class="form-control" type="number" name="item_qty" value="" >    
                               <button type="submit" class="btn btn-primary"> <i class="bi bi-cart-plus"></i> </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php }
           echo "</div>";
        echo "</div>";
         }
         else{
             echo "<h4> No Records Found.</h4>";
         }
        ?>
        </div>
        
    </div>
</div>
     
</body>
<?php mysqli_close($conn);?>
<script src="../js/bootstrap.min.js"></script>
</html>
