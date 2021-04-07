<?php
include_once "includes/db_conn.php";
$searchkey="";
if (isset($_GET['searchkey'])){
    $searchkey=htmlentities($_GET['searchkey']);  
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lecture : SQL Integration with PHP</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
</head>
<body>
       
<div class="container-fluid">
    <div class="row ">
        <div class="col-5 mt-3">
        <form action="index.php" method="GET" >
           <div class="input-group">
            <input id="searchbar" name="searchkey" type="text" class="form-control" placeholder="search">
            <button class="btn btn-outline-primary"> Search <i class="bi bi-search"></i> </button>
           </div>
        </form>
        </div>
        
        <div class="col-1">
            <button class="btn btn-outline-primary"> in </button>
        </div>
        
    </div>
    <div class="row">
        <div class="col-12">
        <?php
        if($searchkey == ""){
           $sql = "SELECT i.item_id
                     , i.item_name
                     , i.item_short_code
                     , c.cat_desc
                     , i.item_price
                  FROM `items` i
                  JOIN `category` c
                    ON i.cat_id = c.cat_id ;";
        
        $stmt=mysqli_stmt_init($conn);
         if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "Statement Failed.";
        exit();
         }
        }
        else{
            $sql = "SELECT i.item_id
                     , i.item_name
                     , i.item_short_code
                     , c.cat_desc
                     , i.item_price
                  FROM `items` i
                  JOIN `category` c
                    ON i.cat_id = c.cat_id
                 WHERE i.item_name LIKE ?
                    OR i.item_short_code = ?
                    OR c.cat_desc = ? 
                    OR i.item_price = ?;";
        
        $stmt=mysqli_stmt_init($conn);
         if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "Statement Failed.";
        exit();
         }
        $itemname="%{$searchkey}%";
        mysqli_stmt_bind_param($stmt, "ssss" , $itemname, $searchkey, $searchkey, $searchkey);
        }
        
        
         mysqli_stmt_execute($stmt);
                     $resultData = mysqli_stmt_get_result($stmt);
         $arr=array();
         while($row = mysqli_fetch_assoc($resultData)){
             array_push($arr,$row);
         } 
         if(!empty($arr)){
            echo "<table class='table'>";
            echo "<thead>";
            echo "<th> Item Name </th>";
            echo "<th> Short Code </th>";
            echo "<th> Category </th>";
            echo "<th> Price </th>";
            echo "</thead>";
             //--------------------------
             foreach($arr as $key => $val){
            echo "<tr>";
             echo "<td>" . $val['item_name']       . "</td>";
             echo "<td>" . $val['item_short_code'] . "</td>";
             echo "<td>" . $val['cat_desc']        . "</td>";
             echo "<td> Php ". $val['item_price']      . "</td>";
            echo "</tr>";
             }
            //-----------------------------
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

<script src="js/bootstrap.min.js"></script>
</html>