<html>
<head>
    <meta charset="UTF-8">
    <title>Lecture : SQL Integration with PHP</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">
</head>
<body>

<div class="container-fluid my-5">
  <div class="row">
    <div class="col-2"></div>
     <div class="col-3">
         <div class="card">
            <div class="card-header">
             <h3 class="card-title text-secondary">Login</h3>   
             <?php
                if(isset($_GET['signup'])){ ?>
                    <div class="alert alert-success">
                        <p class=""> <i class="bi bi-check"></i> Registration Complete.You may now login.</p>
                    </div>
                <?php }
                ?>
            </div>
            <form action="includes/processlogin.php" method="post">     
               <div class="card-body">
                     <input name="p_username" type="text" class="form-control mb-3" placeholder="username or email address">
                     <input name="p_password" type="password" class="form-control mb-3" placeholder="password">
               </div>
               <div class="card-footer pb-n1">
                 <button class="btn btn-primary">Login</button>
                 <a href="createaccount/" class="btn btn-link">Create an Account.</a>
             </div>
             </form>
         </div>
         
     </div>
     <div class="col-7"></div>
  </div>    
</div>

</body>
<script src="js/bootstrap.min.js"></script>
</html>