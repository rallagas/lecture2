<html>
<head>
    <meta charset="UTF-8">
    <title>Lecture : JQUERY </title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../font/bootstrap-icons.css">
</head>
<body>
<?php
    session_start();
    include_once "../includes/func.inc.php";
    include_once "../includes/db_conn.php";
    ?>

<div class="container-fluid">
        <nav class="navbar navbar-expand-lg bg-light text-white">
            <div class="container-fluid">
            <button class="navbar-toggler btn btn-outline-orange" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="bi bi-list"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                  <li class="nav-item">
                    <a href="#cartList" class="nav-link btn btn-no-border-orange"
                           data-bs-toggle="collapse" 
                               role="button"  
                               aria-expanded="false"  
                               aria-controls="cartList"
                    >
                        <i class="bi bi-cart"></i> Cart  
                        <span class="badge bg-danger">
                           <?php echo getCartCount($conn,$_SESSION['user_id']);?>
                        </span>
                        
                    </a>
                    <!--Navigation button to show the form to add item button--->
                 </li>
                 <li class="nav-item">
                     <a href="../customer/index.php" class="nav-link btn btn-no-border-orange"> 
                        <i class="bi bi-app-indicator"></i> Classic Panel
                     </a>                     
                 </li>
                 <li class="nav-item">
                     <a href="../includes/processlogout.php" class="nav-link btn btn-no-border-orange"> 
                        <i class="bi bi-power"></i> Logout
                     </a>                     
                 </li>
                </ul>
            </div>
             </div>
         </nav>
  <div class="row" id="filters">
     <form id="FormSearch" method="post">
      <div class="col-12">
           <div class="form-check form-check-inline">
                   <input type="checkbox" class="form-check-input" id="uncheckall">
                   <label for="uncheckall" class="form-check-label">All</label>
           </div>
                <?php 
                $cat = getCategories($conn);
                foreach($cat as $key => $c){?>
                   <div class="form-check form-check-inline">
                    <input type="checkbox" name="categ[]" class="form-check-input catlist" id="cat<?php echo $c['cat_id'];?>" value="<?php echo $c['cat_id'];?>">
                    <label for="cat<?php echo $c['cat_id'];?>" class="form-check-label"><?php echo $c['cat_desc'];?></label>
                   </div>
                <?php } ?>
            <div class="input-group">
               
             <input type="text" id="searchBar" class="form-control" name="Search" value="">  
             <button class="btn btn-link" id="searchbtn"> <i class="bi bi-search"></i> </button>   
             
             <p class="form-text lead" id="searchStat"></p>
             </div>
     </div>
    </form>    
      
  </div>
  <div class="row" id="midContent"></div>      
</div>

</body>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-3.5.1.min.js"></script>
<script src="own_ajax.js"></script>

</html>