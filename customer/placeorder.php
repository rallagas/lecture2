<?php
session_start();
include_once "../includes/db_conn.php";
include_once "../includes/func.inc.php";
$searchkey=NULL;
$page='placeorder';
if(isset($_GET['order_done']) && isset($_SESSION['place_order'])){
      $_ordernumber = $_SESSION['place_order']['order_number'];
             $_tatp = $_SESSION['place_order']['total_amt_to_pay'];
    
    $order_stat = placeOrder($conn, $_SESSION['user_id'], $_ordernumber, $_tatp);
    header("location: placeorder.php?placeorder={$order_stat}");
    exit();
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

    <div class="container">
        <div class="row pt-5" id="NavigationPanel">

            <?php include_once "cust_nav.php"?>

        </div>

        <div class="row mt-5">

            <div class="col-lg-12 col-sm-12 col-md-12">
                <h4 class="display-4 text-light">Orders to Receive </h4>
                <table class="table table-hover table-responsive bg-light shadow mb-3">
                    <thead class="bg-warning">
                        <th>Order Number</th>
                        <th>Quantity</th>
                        <th>Total Amount to Pay</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <?php   
                        $checkouts = getOrderList($conn, $_SESSION['user_id']);
                        $total = array('amt' => 0.00, 'qty' => 0);
                        foreach($checkouts as $chk_key => $chk){ ?>
                        <tr>
                            <td class="align-middle"><?php echo $chk['order_ref_num']; ?></td>
                            <td class="align-middle"><?php echo $chk['total_item_qty'] . pcpcs($chk['total_item_qty']); ?></td>
                            <td class="align-middle"><?php echo  nf2($chk['total_amt_to_pay']); ?></td>
                            <td><?php switch($chk['status']){
                                        case 'X': echo 'Delivered and Paid'; break;
                                        case 'C': echo 'Checked Out and Waiting to be Delivered'; break;
                                } ?>
                            </td>
                        </tr>
                        <?php
                        $total['amt'] += $chk['total_amt_to_pay'];                                       
                        $total['qty'] += 1;                                       
                        }
                            
                        ?>
                        <tr class="bg-warning">
                            <td class="text-center">Total:</td>
                            <td><b><?php echo $total['qty'] . pcpcs($total['qty']); ?></b></td>
                            <td colspan="2"><b class='text-danger'><?php echo "Php ".number_format($total['amt'],2); ?></b></td>

                        </tr>
                    </tbody>
                </table>


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
