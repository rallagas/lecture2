   <?php
      include_once "../includes/db_conn.php";
      include_once "../includes/func.inc.php";
      ?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lecture : JQUERY </title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../font/bootstrap-icons.css">
</head>
<body>

<div class="container-fluid my-5">
  <div class="row">
   <?php
      $display = fullDisplay($conn);
        foreach($display as $key => $item){ ?>
            
    <div class="col-sm-12 col-lg-2 col-md-2">
        <div class="card">
            <div class="card-header">
                <img src="../images/<?php if($item['item_img'] != ""){ echo $item['item_img']; }else{ echo "200x200.png";} ?>" class="card-img-top">
            </div>
            <div class="card-body">
                <h3 class="card-title"><?php echo $item['item_name'];?></h3>
                <p class="card-text">Php <?php echo number_format($item['item_price'],2);?></p>
            </div>
            <div class="card-footer">
                <form action="" class="orderthisform">
                    <div class="input-group">
                        <input hidden value="1" type="text" class="form-control">
                        <button type="submit" class="btn btn-primary"> <i class="bi bi-cart-plus"></i> </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
            
        <?php 
        }
      ?>
    
  </div>    
</div>

</body>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-3.5.1.min.js"></script>
<script src="../js/ajax.js"></script>

</html>