<!DOCTYPE html>
<?php include_once "includes/db_conn.php"; ?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lecture : SQL Integration with PHP - SELECT</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
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
                    <!--Navigation button to show the form to add item button--->
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
    <div class="row" id="NotificationPanel">
        <div class="col-3"></div>
        <div class="col-6">
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
        </div>
        <div class="col-3"></div>
    </div>
<div class="row" id="contentPanel">
  <div class="col-12">
  <?php
  //declare the SQL
  //Scenario: I wanted to show item_id, item_name, item_short_code
  //          category description, price
  $sql = "SELECT i.item_id
            , i.item_name
            , i.item_short_code
            , c.cat_desc
            , i.item_price
         FROM `items` i
         JOIN `category` c
           ON i.cat_id = c.cat_id ;";
  //initialize MYSQL statement connection to the database.
  //$conn is a variable declared inside db_conn.
  $stmt=mysqli_stmt_init($conn);
  //prepare the statement
  if (!mysqli_stmt_prepare($stmt, $sql)){
     echo "Statement Failed.";
     exit();
  }
  //it will execute the statement 
   mysqli_stmt_execute($stmt);
  //get the results of the executed statement and put it into a variable
   $resultData = mysqli_stmt_get_result($stmt);
  //declare a container array.
   $arr=array();
   while($row = mysqli_fetch_assoc($resultData)){
       //we will do the transfer of data to another array to test 
       //if there is a result.
       array_push($arr,$row);
   }
   // if the new array is not empty, display the tabular representation
   // as HTML
   if(!empty($arr){
      echo "<table class='table'>";
      echo "<thead>";
      echo "<th> Short Code </th>";
      echo "<th> Item Name </th>";
      echo "<th> Category </th>";
      echo "<th> Price </th>";
      echo "<th> Actions </th>";
      echo "</thead>";
      foreach($arr as $key => $val){
      echo "<tr>";
          echo "<td>" . $val['item_short_code'] . "</td>";
          echo "<td>" . $val['item_name']       . "</td>";
          echo "<td>" . $val['cat_desc']        . "</td>";
          echo "<td> Php ". number_format($val['item_price'],2) . "</td>";
          echo "<td> <a href='orderform.php?itemid=".$val['item_id']."' class='btn btn-primary'>order this item</a> </td>";
      echo "</tr>";
      }
      echo "<tr >";
          echo "<td colspan=4 class='text-center'><em>End of result</em></td>";
      echo "</tr>";
  echo "</table>";
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
<script src="js/bootstrap.min.js"></script>
</html>