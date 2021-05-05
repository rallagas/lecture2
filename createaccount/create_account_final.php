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
if(isset($_POST['create_account_complete'])){
    $cust_ref_number = htmlentities($_POST['cust_ref_number']);
    $firstname = htmlentities($_POST['p_fname']);
    $midname = htmlentities($_POST['p_mname']);
    $lastname = htmlentities($_POST['p_lname']);
    $address1 = htmlentities($_POST['p_address_1']);
    $province = htmlentities($_POST['p_province']);
    $cityMun = htmlentities($_POST['p_municipality']);
    $brgy = htmlentities($_POST['p_brgy']);
    $zipcode = htmlentities($_POST['p_zipCode']);
    $gender =  htmlentities($_POST['p_gender']);
    
    $email = htmlentities($_POST['p_email']);
    $p_username = htmlentities($_POST['p_username']);
    $p_cpassword = htmlentities($_POST['p_cpassword']);
    $p_password = htmlentities($_POST['p_password']);
$errormsg = array();

if(setisEmpty($firstname, $midname, $lastname, $address1, $zipcode, $gender, $email,$p_username, $p_cpassword, $p_password  ) !== false){
    array_push($errormsg, "<i class='bi bi-exclamation-triangle'></i> Please fill up all Required Fields.");
}
if($p_cpassword !== $p_password ){
    array_push($errormsg, "<i class='bi bi-exclamation-triangle'></i> Password Mismatch.");
}
if(userNameExists($conn, $p_username) !== false){
    array_push($errormsg, "<i class='bi bi-exclamation-triangle'></i> Username Exists.");
}
?>

<div class="container my-4">
  <div class="row">
    <div class="col-md-12">
            <h3 class="display-5">Create an Account</h3>
            <h4>Ref Num: <?php echo $cust_ref_number; ?></h4>
            <?php
            if(sizeof($errormsg) > 0){
               for($i =0; $i < sizeof($errormsg); $i++){ ?>
                        <div class="badge bg-danger text-light"><?php echo $errormsg[$i];?></div>
               <?php } ?>
                        <div class="shadow px-3 py-3">
            <form action="create_account_final.php" method="POST">
            <input type="text" hidden name="cust_ref_number" value="<?php echo $cust_ref_number; ?>">
            <div class="card-body">
                     <div class="container-fluid">
                    <!-- part I -->
                       <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                 <div class="form-floating">
                                    <input type="text" readonly name="p_fname" id="p_fname" value="<?php echo $firstname;?>" class="form-control text-secondary">     
                                    <label for="p_fname">First Name</label>
                                 </div>
                             </div>
                             <div class="col-sm-12 col-md-12 col-lg-4">
                                 <div class="form-floating">
                                    <input type="text" readonly name="p_lname" id="p_lname" value="<?php echo $lastname;?>"  class="form-control text-secondary">     
                                    <label for="p_lname">Last Name</label>
                                 </div>
                             </div>
                             <div class="col-sm-12 col-md-12 col-lg-4">
                                 <div class="form-floating">
                                    <input type="text" readonly name="p_mname" id="p_mname"  value="<?php echo $midname;?>"  class="form-control text-secondary">     
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
                                   <select name="p_brgy" id="p_brgy" class="form-select">
                                   <option value="<?php echo $brgy; ?>"> <?php echo getAddressDesc($conn, 'B', $brgy); ?> </option>
                                    </select>
                                    <label for="p_brgy">Brgy</label>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-floating">
                               <input type="text" id="p_zipCode" name="p_zipCode" value="<?php echo $zipcode;?>" class="form-control">
                               <label for="p_zipCode">Zip Code </label>
                            </div>
                        </div>
                        <div class="col-6">
                        <p class="lead px-2 py-2">Gender 
                               <input type="radio" name="p_gender" class="btn-check" id="male_opt" value="M" autocomplete="off" <?php if($gender == "M"){ echo "checked"; } ?> >
                               <label class="btn btn-outline-secondary mx-3 " for="male_opt">Male </label>
 
                               <input type="radio" name="p_gender" class="btn-check" id="female_opt" value="F"  autocomplete="off" <?php if($gender == "F"){ echo "checked"; } ?> >
                               <label class="btn btn-outline-secondary mx-3" for="female_opt">Female </label>
                               
                               <input type="radio" name="p_gender" class="btn-check" id="rns_opt" value="X"  autocomplete="off" <?php if($gender == "X"){ echo "checked"; } ?>>
                               <label class="btn btn-outline-secondary mx-3" for="rns_opt">Rather Not Say </label>
                        </p>   
                        </div>
                    </div>
                    <div class="row mt-3">
                    <hr>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                                 <div class="form-floating">
                                    <input type="email" name="p_email" id="p_email" value="<?php echo $email;?>" class="form-control">     
                                    <label for="p_email">Email Address</label>
                                 </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                                 <div class="form-floating">
                                    <input type="text" name="p_username" id="p_username" value="<?php echo $p_username;?>"  class="form-control">     
                                    <label for="p_username">Username</label>
                                 </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                                 <div class="form-floating">
                                    <input type="password" name="p_password" id="p_password" value="<?php echo $p_password;?>" class="form-control">     
                                    <label for="p_password">Password</label>
                                 </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                                 <div class="form-floating">
                                    <input type="password" name="p_cpassword" id="p_cpassword" value="<?php echo $p_cpassword;?>" class="form-control">     
                                    <label for="p_cpassword">Confirm Password</label>
                                 </div>
                        </div>
                    </div>

                </div>
                     
            </div>
        </div>
            <div class="card-footer">
               <div class="d-flex flex-row-reverse">
                <button class="btn btn-primary" name="create_account_complete"> Submit <bi class="bi-caret-right"></bi></button>
                </div>
            </div>
            </form>
        </div>
<?php  }
            else
            { 
                if(createCustomer($conn,$cust_ref_number,$p_username, $p_password, $email,$firstname,$lastname,$midname,$address1,$brgy,$cityMun,$province,$zipcode, $gender)!== false){
                    header("location: ../index.php?signup=1");
                }
                else{ ?>
                    <div class="alert alert-danger"><?php echo "Somethng Went Wrong";?></div>
                <?php }
            } ?>
    </div>
  </div>    
</div>
<?php } ?>
</body>
<script src="../js/bootstrap.min.js"></script>
</html>