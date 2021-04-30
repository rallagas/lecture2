<?php
include_once "../includes/db_conn.php";
include_once "../includes/func.inc.php";
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lecture : SQL Integration with PHP</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../font/bootstrap-icons.css">
</head>
<body>
<?php
if(isset($_POST['create_account_2'])){
    $firstname = htmlentities($_POST['p_fname']);
    $midname = htmlentities($_POST['p_mname']);
    $lastname = htmlentities($_POST['p_lname']);
    $address1 = htmlentities($_POST['p_address_1']);
    $province = htmlentities($_POST['p_province']);
    $cityMun = htmlentities($_POST['p_municipality']);
    ?>
<div class="container my-4">
  <div class="row">
    <div class="col-md-12">
            <h3 class="display-5">Create an Account</h3>
        <div class="shadow px-3 py-3">
    
            <form action="create_account_final.php" method="POST">
            <input type="text" name="cust_ref_number" value="<?php echo get_random_figures($firstname . $lastname . $midname);?>">
            <div class="card-body">
                     <div class="container-fluid">
<!-- part I -->
                       <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                 <div class="form-floating">
                                    <input type="text" name="p_fname" id="p_fname" value="<?php echo $firstname;?>" class="form-control text-secondary">     
                                    <label for="p_fname">First Name</label>
                                 </div>
                             </div>
                             <div class="col-sm-12 col-md-12 col-lg-4">
                                 <div class="form-floating">
                                    <input type="text" name="p_lname" id="p_lname" value="<?php echo $lastname;?>"  class="form-control text-secondary">     
                                    <label for="p_lname">Last Name</label>
                                 </div>
                             </div>
                             <div class="col-sm-12 col-md-12 col-lg-4">
                                 <div class="form-floating">
                                    <input type="text" name="p_mname" id="p_mname"  value="<?php echo $midname;?>"  class="form-control text-secondary">     
                                    <label for="p_mname">Middle Name</label>
                                 </div>
                             </div>
                         </div>

                         <div class="row mt-4">
                             <div class="col-6">
                                 <div class="form-floating">
                                    <input type="text" name="p_address_1" id="p_address_1"  value="<?php echo $address1;?>"  class="form-control text-secondary">     
                                    <label for="p_address_1">Address 1</label>
                                 </div>
                             </div>
                             <div class="col-6">
                                 <div class="form-floating">
                                    <select name="p_province" id="p_province" class="form-select text-secondary">
                                       <option value="<?php echo $province; ?>"> <?php echo getAddressDesc($conn, 'P', $province); ?></option>
                                    </select>
                                    <label for="p_province">Province</label>
                                 </div>
                             </div>
                         </div>

                     <!-- part II -->
                    <div class="row mt-4">
                        <div class="col-6">
                             <div class="form-floating">
                                    <select name="p_municipality" id="p_municipality" class="form-select">
                                       <option value="<?php echo $cityMun; ?>"> <?php echo getAddressDesc($conn, 'C', $cityMun); ?> </option>
                                    </select>
                                    <label for="p_province">City / Municipality</label>
                                 </div>
                        </div>
                         <!-- part III -->
                        <div class="col-6">
                            <div class="form-floating">
                                   <select name="p_brgy" id="p_brgy" class="form-select text-primary shadow">
                                       <option>--SELECT CITY / BRGY--</option>
                                        <?php
                                        $brgyList = fetchAddress($conn,'B',$cityMun);
                                        foreach ($brgyList as $key => $brgy){ ?>
                                            <option value="<?php echo $brgy['brgyCode']; ?>"><?php echo $brgy['brgy_nm']; ?></option>
                                        <?php } ?>
                                        
                                    </select>
                                    <label for="p_province">Brgy</label>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-floating">
                               <input type="text" id="p_zipCode" name="p_zipCode" class="form-control text-primary shadow">
                               <label for="p_zipCode">Zip Code </label>
                            </div>
                        </div>
                        <div class="col-6">
                        <p class="lead shadow px-2 py-2">Gender 
                               <input type="radio" name="p_gender" class="btn-check" id="male_opt" value="M" autocomplete="off">
                               <label class="btn btn-outline-secondary mx-3 " for="male_opt">Male </label>
 
                               <input type="radio" name="p_gender" class="btn-check" id="female_opt" value="F"  autocomplete="off">
                               <label class="btn btn-outline-secondary mx-3" for="female_opt">Female </label>
                               
                               <input type="radio" name="p_gender" class="btn-check" id="rns_opt" value="X"  autocomplete="off" checked>
                               <label class="btn btn-outline-secondary mx-3" for="rns_opt">Rather Not Say </label>
                        </p>   
                        </div>
                    </div>
                    <div class="row mt-3">
                    <hr>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                                 <div class="form-floating">
                                    <input type="email" name="p_email" id="p_email" class="form-control text-primary shadow">     
                                    <label for="p_email">Email Address</label>
                                 </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                                 <div class="form-floating">
                                    <input type="text" name="p_username" id="p_username" class="form-control text-primary shadow">     
                                    <label for="p_username">Username</label>
                                 </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                                 <div class="form-floating">
                                    <input type="password" name="p_password" id="p_password"  class="form-control text-primary shadow">     
                                    <label for="p_password">Password</label>
                                 </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                                 <div class="form-floating">
                                    <input type="password" name="p_cpassword" id="p_cpassword"  class="form-control text-primary shadow">     
                                    <label for="p_cpassword">Confirm Password</label>
                                 </div>
                        </div>
                    </div>

                </div>
                     
            </div>
        </div>
            <div class="card-footer">
               <div class="d-flex flex-row-reverse">
                <button class="btn btn-primary" name="create_account_complete"> Complete <bi class="bi-caret-right"></bi></button>
                </div>
            </div>
            </form>
        </div>
        
    </div>
  </div>    
</div>
<?php
}
else{
    header("location: ../createaccount");
    exit;
}
?>


</body>
<script src="../js/bootstrap.min.js"></script>
</html>