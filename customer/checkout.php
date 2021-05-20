<?php
session_start();
include_once "../includes/db_conn.php";
include_once "../includes/func.inc.php";
$searchkey=NULL;
$page='checkout';
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

            <?php include_once "cust_nav.php"?>

        </div>

        <div class="row mt-5">
            <div class="col-lg-3"></div>
            <div class="col-lg-7 col-sm-12 col-md-12">
                <h4 class="display-4 text-dark fw-bold">Checkout </h4>
                <table class="table table-hover table-responsive bg-light shadow mb-3">
                    <thead class="bg-warning">
                        <th></th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php   
                        $checkouts = getCheckedOutList($conn, $_SESSION['user_id']);
                        $total = array('amt' => 0.00, 'qty' => 0);
                        foreach($checkouts as $chk_key => $chk){ ?>
                        <tr>
                            <td class="w-25">
                                <img src="../images/<?php echo $chk['item_img'] == '' ? "200x200.png" : $chk['item_img']; ?>" alt="1 x 1" class="rounded w-75">
                            </td>
                            <td class="align-middle"><?php echo $chk['item_name']; ?></td>
                            <td class="align-middle"><?php echo "Php " . number_format($chk['item_price'],2); ?></td>
                            <td class="align-middle"><?php echo $chk['total_item_qty'] . ($chk['total_item_qty'] > 1 ? ' pcs' : 'pc'); ?></td>
                            <td class="align-middle"><?php echo "Php " . number_format($chk['total_order_amt'],2); ?></td>
                            <td class="align-middle"> <a href="?deletecartitem=<?php echo $chk['item_id']; ?>" class="btn text-secondary btn-outline-light" title="Remove "><i class="bi bi-x"></i></a> </td>
                        </tr>
                        <?php
                        $total['amt'] += $chk['total_order_amt'];                                       
                        $total['qty'] += $chk['total_item_qty'];                                       
                        }
                            
                        ?>
                        <tr class="bg-warning">
                            <td colspan="3" class="text-center">Total:</td>
                            <td><b><?php echo $total['qty'] . pcpcs($total['qty']); ?></b></td>
                            <td colspan="2"><b class='text-danger'><?php echo "Php ".number_format($total['amt'],2); ?></b></td>

                        </tr>
                    </tbody>
                </table>
                <table class="table-responsive table bg-light rounded-3">
                    <thead class="bg-warning">
                        <th>Shipping Fee</th>
                        <th>Shipping Method</th>
                        <th>Discount %</th>
                        <th>Discount Amount</th>
                        <th><span class="badge bg-success text-light">+SF</span>
                            Discounted Total Amount
                        </th>
                        <th>After Tax (12% VAT)</th>
                        <th>Total Amount to Pay</th>
                    </thead>
                    <tbody>
                        <tr>
                            <?php $fees = getCheckedFees($conn);?>
                            <td><?php 
                             
                               if($total['qty'] == 0){
                                   $shipping_fee = 0;
                                   echo "<span class='badge text-dark'>". nf2($shipping_fee) . "</span>";
                               }
                            else{
                                  $shipping_fee = ($total['qty'] >= $fees['min_promo_purchase'] ? $fees['promo_shipping_fee'] :  $fees['shipping_fee'] ) ;
                                 echo  ($shipping_fee == $fees['promo_shipping_fee'] ? "<span class='badge bg-warning text-secondary text-decoration-line-through'>" . nf2($fees['shipping_fee']) .  "</span> <span class='badge bg-light text-success'>" . nf2($fees['promo_shipping_fee']) . "</span>" :  "<span class='badge text-dark'>". nf2($fees['shipping_fee']) . "</span>" ) ;   
                            }
                             
                            ?>
                            </td>
                            <td> <?php 
                            switch ($fees['delivery_mode']){
                                case 'COD': echo "<i class='bi bi-cash-stack'></i> Cash on Delivery Only";        
                                    break;
                                case 'CARD' : echo "<i class='bi bi-credit-card'></i> Debit/Credit Card";        
                            }
                            
                            
                            ?></td>
                            <td>
                                <?php
                               $discount_perc = ($total['amt'] >= $fees['min_purchase_amt'] ? ($fees['global_promotion_perc'] * 100) : 0.00 );
                               echo $discount_perc . "%";
                            ?>
                            </td>
                            <td>
                                <?php
                               $discount_amt = $total['amt'] * ($discount_perc/100);
                               if ($discount_amt > 0 && $discount_amt <= $fees['max_discount_amt']){
                                   echo "<span class='badge text-light bg-success'>".nf2($discount_amt)."</span>" ;
                               } 
                             else if($discount_amt > $fees['max_discount_amt']){
                                 $discount_amt = $fees['max_discount_amt'];
                                 echo "<span class='badge text-dark bg-light'>".nf2($discount_amt)."</span>";
                             }
                               else{
                                   echo "<span class='badge text-dark bg-light'>".nf2($discount_amt)."</span>";
                               }
                            ?>
                            </td>
                            <td><?php $new_amount = $total['amt'] - $discount_amt; echo $new_amount; ?></td>
                            <td><?php echo $fees['tax'] == 'W' ? 'Waived' :  "Php " . $new_amount * 0.87; ?></td>
                            <td><span class="text-light bg-success fs-6 p-2">
                                    <?php 
                               $total_amount_to_pay = $fees['tax'] == 'W' ? $discount_amt + $shipping_fee :  ($new_amount * 0.87) + $shipping_fee ;
                                 echo $fees['tax'] == 'W' ? nf2($discount_amt + $shipping_fee) :  nf2(($new_amount * 0.87) + $shipping_fee) ;
                                     $_SESSION['place_order'] = array(
                                          'order_number' =>getRandom() , 
                                          'total_amt_to_pay' => $total_amount_to_pay ,
                                          'total_qty' => $total['qty']
                                     ); 
                                     
                                ?>
                                </span></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2">

                                <a href="placeorder.php?order_done=1" class="float-end btn btn-lg border-3 border-warning btn-outline-warning text-danger"> Place Order <i class="bi bi-cart-check-fill"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
            <div class="col-lg-2">

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
