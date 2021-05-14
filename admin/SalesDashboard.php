<?php
session_start();
include_once "../includes/db_conn.php";
include_once "../includes/func.inc.php";
include_once "../includes/utilities.inc.php";
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
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../font/bootstrap-icons.css">

</head>

<body>
    <header class="navbar navbar-light sticky-top bg-light flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="index.php"> <i class="bi bi-house"></i> Sales Dashboard </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">Sign out</a>
            </li>
        </ul>
    </header>


    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">

                <div class="list-group">
                    <a href="?cat=all&cat_n=All Category" class="list-group-item list-group-item-action">All</a>
                    <?php $category_list = getCategories($conn);
                                        if(!empty($category_list)){
                                            foreach($category_list as $catKey => $cat){ ?>
                    <a href="?cat=<?php echo $cat['cat_id'];?>&cat_n=<?php echo $cat['cat_desc'];?>" class="list-group-item list-group-item-action">
                        <?php echo $cat['cat_desc'];?>
                    </a>
                    <?php   
                                           }
                                            
                                        }
                                        else{ ?>
                    <li class="list-group-item">
                        No Categories
                    </li>
                    <?php }                    
                    ?>
                </div>

                <?php
                if(isset($_GET['f'])){
                    $it = cleanstr($_GET['f_item']);
                    $sd = cleanstr($_GET['f_date1']);
                    $ed = cleanstr($_GET['f_date2']);
                }else{
                    $it = NULL;
                    $sd = NULL;
                    $ed = NULL;
                    
                    
                }
                ?>
                <div class="card bg-light">
                    <div class="card-body">
                        <form action="" method="get">
                            <div class="input-group mt-3">
                                <span class="input-group-text">Item</span>
                                <input value="<?php echo $it; ?>" type="text" id="f_item" class="form-control" name="f_item">
                            </div>
                            <div class="input-group mt-3">
                                <span class="input-group-text">Start Date</span>
                                <input value="<?php echo $sd; ?>" required type="date" id="f_date1" class="form-control" name="f_date1">
                            </div>
                            <div class="input-group mt-3">
                                <span class="input-group-text">End Date</span>
                                <input value="<?php echo $ed; ?>" required type="date" id="f_date2" class="form-control" name="f_date2">
                            </div>
                            <div class="mt-3">
                                <button name="f" value="filter" class="btn btn-outline-primary">Filter Data</button>
                            </div>
                        </form>
                    </div>


                </div>

            </div>
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php 
                 if(isset($_GET['f'])){ //this means the filter button has been triggered
                        $item=null;
                        $item_info = query($conn, "SELECT * FROM `items`");
                        $where=null;
                        if(isset($_GET['f_item'])){
                            $item = htmlentities($_GET['f_item']);
                            $s = "%{$item}%";
                            $item_info = query($conn, "SELECT * FROM `items` WHERE item_name LIKE ? ;" , array($s));
                        }
                        $start_date = htmlentities($_GET['f_date1']);
                        $end_date = htmlentities($_GET['f_date2']); ?>

                <p class="lead">Results for <?php echo $start_date;?> to <?php echo $end_date; echo $item == NULL ? '' : " and items similar to `{$item}`"; ?></p>
                <?php foreach($item_info as $k => $item){ 
                   $sales_info = getSalesPerfItem($conn, $item['item_id'], array($start_date, $end_date)); 
                ?>
                <h3 class="display-6"><?php echo $item['item_name'];?> </h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <th>Transaction Date</th>
                            <th>Net Sales</th>
                            <th>Total Count Ordered</th>
                        </thead>
                        <?php  if(!empty($sales_info)){  ?>
                        <tbody>
                            <?php foreach($sales_info as $s => $sale){ ?>
                            <tr class="text-success">
                                <td><?php echo $sale['date_ordered'];?></td>
                                <td><?php echo $sale['total_net_sale'];?></td>
                                <td><?php echo $sale['total_item_ordered'];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <?php } else{ ?>
                        <tbody>
                            <tr>
                                <td colspan="3">
                                    <p class="text-danger">No Sales Available</p>
                                </td>

                            </tr>
                        </tbody>
                        <?php } ?>
                    </table>
                </div>

                <?php
                 }
            } //end filter result
                
                
            if(isset($_GET['cat'])){ //sales for category
                $catid=htmlentities($_GET['cat']);
                if($catid !== 'all'){ ?>

                <h3><?php echo htmlentities($_GET['cat_n'] === NULL ? '' : $_GET['cat_n']);?></h3>

                <?php $catSales = getSalesPerfCat($conn, $catid); 
                    if(!empty($catSales)){ //sales not empty
                ?>
                <table class="table table-responsive">
                    <thead>
                        <th>Transction Date</th>
                        <th>Net Sales</th>
                        <th>Order Qty</th>
                    </thead>
                    <?php foreach($catSales as $k => $cs){
                        
                        
                    } ?>
                </table>
                <?php
                    } //sales not empty
                    else{ ?>
                <p class="lead">No Sales</p>
                <?php }
                }
                else{ ?>
                <h3><?php echo htmlentities($_GET['cat_n'] === NULL ? '' : $_GET['cat_n']);?>
                    <span class="lead">( Sales performance from Day -30)</span>
                </h3>
                <?php $categ = query($conn, "SELECT * FROM `category`; "); ?>
                <div class="container-fluid">
                    <div class="row align-items-start">

                        <?php foreach($categ as $k => $c){
                        $tot_cat_sale = 0.00;?>

                        <div class="col-lg-3 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo $c['cat_desc'];?></h3>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <?php $item_categ = query($conn, "SELECT * FROM `items` WHERE cat_id = ?; ", array($c['cat_id']));
                                    foreach($item_categ as $x => $ic){ ?>
                                        <span class="list-group-item">
                                            <?php
                                    $item_sale =  query($conn, "SELECT sum(i.item_price * c.item_qty) total_net_sales, sum(c.item_qty) total_ordered  FROM `cart` c JOIN `items` i on (i.item_id = c.item_id) WHERE i.item_id = ? and c.status in ('C','X') and confirm = 'Y' and date_ordered >= CURRENT_DATE - 30; ", array($ic['item_id'])); 
                                    
                                    foreach($item_sale as $s => $sale){    ?>
                                            <span class="badge badge-pill <?php echo $sale['total_ordered'] <= 0.00 ? 'bg-secondary' : 'bg-danger';?> float-start"><?php echo $sale['total_ordered']; ?></span>
                                            <?php echo $ic['item_name'];?>
                                            <span class="float-end <?php echo $sale['total_net_sales'] <= 0.00 ? 'text-danger' : 'text-success';?>"><?php echo nf2( $sale['total_net_sales'] ); ?></span>
                                            <?php
                                                                       $tot_cat_sale += $sale['total_net_sales'];
                                                                      } ?>
                                        </span>
                                        <?php }
                                                                      
                                    ?>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <span class="lead">Total: <?php echo nf2($tot_cat_sale); ?></span>

                                </div>


                            </div>
                        </div>

                        <?php } ?>
                    </div>
                </div>

                <?php }
                
                
                
            }
                ?>

            </div>
        </div>

    </div>



</body>
<?php mysqli_close($conn);?>
<script src="../js/bootstrap.min.js"></script>

</html>
