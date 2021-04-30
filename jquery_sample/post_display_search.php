<html>
<head>
    <meta charset="UTF-8">
    <title>Lecture : JQUERY </title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../font/bootstrap-icons.css">
</head>
<body>

<div class="container">
  <div class="row">
     <?php
    include_once "../includes/db_conn.php";
    include_once "../includes/func.inc.php";
      if(isset($_POST['Search'])){
          if(isset($_POST['categ'])){
               $cat = array();
              foreach($_POST['categ'] as $catid){
              array_push($cat, $catid);
              }
             $iteminfo = displayItemInfo($conn,"like_name",htmlentities($_POST['Search']), $cat);       
          }
          else{
             $iteminfo = displayItemInfo($conn,"like_name",htmlentities($_POST['Search']));       
          }
         
      }
      else{
          $iteminfo = displayItemInfo($conn);  
      }
          if(!empty($iteminfo)){
          foreach($iteminfo as $key => $i){ ?>
                          
    <div class="col-sm-6 col-lg-3 col-md-4">
        <div class="card">
            <div class="card-header">
                <img src="../images/<?php if($i['item_img'] != ""){ echo $i['item_img']; }else{ echo "200x200.png";} ?>" class="card-img-top">
            </div>
            <div class="card-body">
                <h3 class="card-title"><?php echo $i['item_name'];?></h3>
                <p class="card-text">Php <?php echo number_format($i['item_price'],2);?></p>
            </div>
            <div class="card-footer">
                <form action="post" class="orderthisform" id="itemid_<?php echo $i['item_id'];?>">
                    <div class="input-group">
                        <input hidden value="1" type="text" class="form-control">
                        <input hidden value="<?php echo $i['item_id']; ?>" type="text" class="form-control">
                        <button type="button" class="btn btn-primary"> <i class="bi bi-cart-plus"></i> </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
            
              
          <?php } 
          }else{
              ?>
              <p class="lead">No Items found.</p>
              <?php
          }
      
      ?>
      
  </div>
</div>

</body>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-3.5.1.min.js"></script>

</html>