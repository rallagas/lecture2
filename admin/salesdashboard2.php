<?php
include_once "../includes/db_conn.php";
include_once "../includes/utilities.inc.php";
include_once "../includes/func.inc.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>Lecture : SQL Integration with PHP</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../font/bootstrap-icons.css">
    <link href="../css/sidebars.css" rel="stylesheet">

    <title>Sidebars · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/">
    <!-- Bootstrap core CSS -->

</head>

<body>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="flex-shrink-0 p-3 bg-white col-3 m-0 border-end">
                    <a href="/" class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none ">
                        <svg class="bi me-2" width="30" height="24">
                            <use xlink:href="#bootstrap" />
                        </svg>
                        <span class="fs-5 fw-semibold">Sales Dashboard</span>
                    </a>

                    <?php include_once "sidebar_nav.php"; ?>

                </div>
                <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white col-9">
                    <span class="d-flex align-items-center flex-shrink-0 p-3 link-dark text-decoration-none border-bottom">
                        <span class="fs-5 fw-semibold">
                            <?php 
                            if(isset($_GET['timeframe'])){
                                
                                 $totals = getTotals($conn);
                                $total_sale = NULL;
                                $total_qty = NULL;
                                            foreach($totals as $tk => $total){
                                                $total_sale = $total['sales'];
                                                $total_qty = $total['item_qty'];      
                                            }
                                
                                switch(cleanstr($_GET['timeframe'])){
                                    case 'D': echo "Daily ";
                                             $sql = "SELECT t.date _when
                                                          , t.year_id
                                                          , sum(item_qty) item_qty
                                                          , sum(i.item_price * c.item_qty) sale
                                                       FROM `lu_day` t
                                                       JOIN `cart` c
                                                         on c.date_ordered = t.date
                                                       JOIN `items` i
                                                         on c.item_id = i.item_id
                                                      GROUP BY t.date , year_id
                                                      ORDER BY t.date ASC ;
                                                    ";
                                            
                                            $prefix = null;
                                        break;
                                    case 'W': echo "Weekly";
                                             $sql = "SELECT t.week_id _when
                                                          , t.year_id
                                                          , sum(item_qty) item_qty
                                                          , sum(i.item_price * c.item_qty) sale
                                                       FROM `lu_day` t
                                                       JOIN `cart` c
                                                         on c.date_ordered = t.date
                                                       JOIN `items` i
                                                         on c.item_id = i.item_id
                                                      GROUP BY t.week_id , year_id
                                                      ORDER BY t.week_id ASC ;
                                                    ";
                                            
                                            $prefix = "Week ";
                                        break;
                                    case 'M': echo "Monthly";
                                         $sql = "SELECT t.period_id _when
                                                          , t.year_id
                                                          , sum(item_qty) item_qty
                                                          , sum(i.item_price * c.item_qty) sale
                                                       FROM `lu_day` t
                                                       JOIN `cart` c
                                                         on c.date_ordered = t.date
                                                       JOIN `items` i
                                                         on c.item_id = i.item_id
                                                      GROUP BY t.period_id , year_id
                                                      ORDER BY t.period_id ASC ;
                                                    ";
                                            $prefix = "Period ";
                                        break;
                                    case 'A': echo "Annually";
                                         $sql = "SELECT  t.year_id _when
                                                          , sum(item_qty) item_qty
                                                          , sum(i.item_price * c.item_qty) sale
                                                       FROM `lu_day` t
                                                       JOIN `cart` c
                                                         on c.date_ordered = t.date
                                                       JOIN `items` i
                                                         on c.item_id = i.item_id
                                                      GROUP BY t.year_id
                                                      ORDER BY t.year_id ASC ;
                                                    ";
                                            $prefix = "Year ";
                                        break;
                                }
                            }
                            ?>
                        </span>
                    </span>
                    <div class="list-group list-group-flush border-bottom scrollarea">
                        <?php
                        
                        $sales = query($conn, $sql);
                        if(!empty($sales) || $sales !== false ){
                              foreach($sales as $sk => $sale){
                                    $perc1 = number_format(($sale['sale']/$total_sale),2) * 100.00;
                                    $perc2 = number_format(($sale['item_qty']/$total_qty),2) * 100.00;
                           ?>

                        <h4 class="fs-4 fw-light text-dark me-3"><?php echo $prefix . " ". $sale['_when']. " - " . $perc1; ?>%</h4>
                        <div class="progress mb-1" style="height: 30px">
                            <div class="progress-bar bg-warning text-dark" role="progressbar" style="width: <?php echo $perc1; ?>%" aria-valuenow="<?php echo $perc1; ?>" aria-valuemin="0" aria-valuemax="100"> <?php echo "Sales :" . nf2($sale['sale']); ?></div>
                        </div>
                        <div class="progress" style="height: 30px">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $perc2; ?>%" aria-valuenow="<?php echo $perc2; ?>" aria-valuemin="0" aria-valuemax="100"> <?php echo "Qty :" . $sale['item_qty'] . pcpcs($sale['item_qty']); ?></div>
                        </div>
                        <?php } 
                        }
                        else{
                            
                            echo "No Sales";
                        }

                        ?>

                    </div>
                </div>
            </div>
        </div>


    </main>


    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../css/sidebars.js"></script>
</body>

</html>
