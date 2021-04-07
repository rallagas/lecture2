<html>
<head>
    <meta charset="UTF-8">
    <title>Lecture : SQL Integration with PHP</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
</head>
<body>
<?php
    if(isset($_POST['item_id'])){
        include "includes/db_conn.php";
        $cust_id = 1;
        $item_id = htmlentities($_POST['item_id']);
        $item_qty = htmlentities($_POST['item_qty']);
        $dateordered="20210310";
        $item_stat='P';
        
         $sql_ins = "INSERT INTO `orders`
                  (`cust_id`, `item_id`, `order_qty`, `order_status`) 
                   VALUES (?,?,?,?);";
        $stmt_ins = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt_ins, $sql_ins)){
        header("location: index.php?error=7"); //insert failed
        exit();
        }
        mysqli_stmt_bind_param($stmt_ins,"ssss",$cust_id,$item_id,$item_qty,$item_stat);
        mysqli_stmt_execute($stmt_ins);
        echo "done with order";
        
    }
    
    
    if(isset($_GET['itemid'])){ ?>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-header">Order Item</div>
                      <form action="orderform.php" method="post">
                    <div class="card-body">
                              <label for="" class="form-label">Item ID</label>
                               <input class="form-control" name="item_id" type="text" value="<?php echo $_GET['itemid']; ?>">
                               <label for="" class="form-label">How Many?</label>
                               <input class="form-control" type="number" name="item_qty">
                    </div>
                    <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </form>
                </div>
 
            </div>
        </div>
    </div>
    <?php } ?>
</body>
<script src="js/bootstrap.min.js"></script>
</html>