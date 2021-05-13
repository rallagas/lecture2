
    <div class="container-fluid" id="item_list">


        <?php
$category_list = getCategories($conn);
if(!isset($searchkey)){
    if(!empty($category_list) || $category_list !== false){
        foreach($category_list as $categ_key => $cat){ ?>
        <div class="row mt-3 p-3 rounded-2 border border-bottom-0 border-info">
            <marker id="cat<?php echo $cat['cat_id']; ?>" class=' mt-5 mb-5'></marker>

            <div class="col-lg-3 col-sm-12 mb-0">
                <h3 class="display-6"><?php echo $cat['cat_desc']; ?></h3>
            </div>

            <div class="col-lg-4 col-sm-12 mt-0">
                <table class="table table-hover table-responsive text-sm">
                    <thead>
                        <th>Date</th>
                        <th>Net Sales</th>
                        <th>Total Item Ordered</th>

                    </thead>
                    <?php
                //sales perf                          
               $cat_sales = getSalesPerfCat($conn, $cat['cat_id']); 
               if(!count($cat_sales)){ ?>
                    <tr>
                        <td colspan="3">No Data Found</td>
                    </tr>
                    <?php }
               else{
                foreach($cat_sales as $sales => $prop){ ?>
                    <tr>
                        <td><?php echo $prop['date_ordered'];?> </td>
                        <td><?php echo nf2($prop['total_net_sale']);?> </td>
                        <td><?php echo $prop['total_item_ordered'];?> </td>

                    </tr>
                    <?php    }
               }
                    ?>
                </table>

            </div>
        </div>
        <div class="row p-3 rounded-2 border-top-0 border border-info">
            <?php
             $menu = showMenu($conn, $cat['cat_id']);
             if(!empty($menu) || $menu !== false ){
                foreach($menu as $key => $val){ ?>


            <?php }
             }
             else{
                 echo "<h4> No Records Found.</h4>";
             }   ?>
        </div>
        <?php }
    }
} 
else{  //with search?>
        <div class="row mt-3" id="resultSetSearch">
            <?php
            echo "<p class='lead'>Result for {$searchkey}:</p><hr>";
             $menu = showMenu($conn, null, $searchkey);
             if(!empty($menu) || $menu !== false ){
                foreach($menu as $key => $val){ ?>
            <div class="col-lg-2 col-md-6 col-sm-6">

                <div class="card">
                    <img src="../images/<?php echo $val['item_img'] == '' ? "200x200.png" : $val['item_img']; ?>" alt="1 x 1" class="card-img-top" style=" height: 300px; width=300px; object-fit: cover">
                    <div class="card-body">

                        <form action="../includes/deleteitem.php" method="post">
                            <input class="form-control mb-1" type="hidden" name="item_id" id="item_id" value="<?php echo $val['item_id']; ?>">
                            <div class="form-floating">
                                <input id="itemname<?php echo $val['item_id']; ?>" class="form-control" type="text" name="item_id" value="<?php echo $val['item_name']; ?>">
                                <label class="form-label" for="itemname<?php echo $val['item_id']; ?>">Item Name</label>
                            </div>
                            <div class="form-floating">
                                <input id="itemprice<?php echo $val['item_id']; ?>" class="form-control mb-1" type="number" name="item_price" value="<?php echo $val['item_price']; ?>">
                                <label class="form-label" for="itemprice<?php echo $val['item_id']; ?>">Item Price</label>
                            </div>
                            <div class="form-floating">
                                <input id="itemsc<?php echo $val['item_id']; ?>" class="form-control mb-1" type="text" name="item_short_code" value="<?php echo $val['item_short_code']; ?>">
                                <label class="form-label" for="itemsc<?php echo $val['item_id']; ?>">Item Short Code</label>
                            </div>
                            <button type="submit" class="btn btn-outline-success position-absolute top-100 start-50 translate-middle" title="Update <?php echo $val['item_name']; ?>"> <i class="bi bi-arrow-counterclockwise"></i> </button>
                        </form>
                        <a class="btn btn-outline-danger position-absolute top-50 start-50 translate-middle" title="Remove <?php echo $val['item_name']; ?>"> <i class="bi bi-x"></i> </a>

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