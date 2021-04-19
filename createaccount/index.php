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

<div class="container my-4">
  <div class="row">
    <div class="col-md-12">
            <h3 class="display-5">Create an Account</h3>
        <div class="shadow px-3 py-3">
        <?php
            if(!isset($_GET['done'])){
            ?>
            <form action="index.php" method="get">
            <input type="number" value="1" name="done" hidden>
            <div class="card-body">
                     <div class="container-fluid">
                         <div class="row">
                            <div class="col-4">
                                 <div class="form-floating">
                                    <input type="text" name="p_fname" id="p_fname" class="form-control">     
                                    <label for="p_fname">First Name</label>
                                 </div>
                             </div>
                             <div class="col-4">
                                 <div class="form-floating">
                                    <input type="text" name="p_lname" id="p_lname" class="form-control">     
                                    <label for="p_lname">Last Name</label>
                                 </div>
                             </div>
                             <div class="col-4">
                                 <div class="form-floating">
                                    <input type="text" name="p_mname" id="p_mname" class="form-control">     
                                    <label for="p_mname">Middle Name</label>
                                 </div>
                             </div>
                         </div>
                         <div class="row mt-4">
                             <div class="col-6">
                                 <div class="form-floating">
                                    <input type="text" name="p_address_1" id="p_address_1" class="form-control">     
                                    <label for="p_address_1">Address 1</label>
                                 </div>
                             </div>
                             <div class="col-6">
                                 <div class="form-floating">
                                    <select name="p_province" id="p_province" class="form-select">
                                       <option>--SELECT PROVINCE--</option>
                                        <?php
                                        $provList = fetchAddress($conn,'P','1');
                                        foreach ($provList as $key => $prov){ ?>
                                            <option value="<?php echo $prov['provCode']; ?>"><?php echo $prov['prov_nm']; ?></option>
                                        <?php } ?>
                                        
                                    </select>
                                    <label for="p_province">Province</label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     
            </div>
            <div class="card-footer">
               <div class="d-flex flex-row-reverse">
                <button class="btn btn-primary"> Next <bi class="bi-caret-right"></bi></button>
                </div>
            </div>
            </form>
        <?php }
            else if(isset($_GET['done'])){
                extract($_GET);
               $done = cleanstr($done);
            switch($done){
                case 1:
                $p1_fname = cleanstr($_GET['p_fname']);
                $p1_lname = cleanstr($_GET['p_lname']);
                $p1_mname = cleanstr($_GET['p_mname']);
                $p1_address_1 = cleanstr($_GET['p_address_1']);
                $p1_province = cleanstr($_GET['p_province']); 
            ?>
                <form action="index.php" method="get">
                   <input type="number" value="2" name="done" >
                   <input type="text" name="p_province" id="p1_province" value="<?php echo p1_province; ?>">     
                   <div class="input-group">
                    <input required type="text" class="form-control" name="p_fname" id="p_fname" value="<?php echo $p_fname; ?>">     
                    <input required type="text" class="form-control" name="p_mname" id="p_mname" value="<?php echo $p_mname; ?>">     
                    <input required type="text" class="form-control" name="p_lname" id="p_lname" value="<?php echo $p_lname; ?>">     
                   </div>
                   <div class="input-group mt-3">                      
                       <input readonly type="text" name="p_address_1" id="p_address_1" value="<?php echo $p_address_1; ?>" class="form-control">         
                       <select class="form-select">
                             <option selected><?php echo getAddressDesc($conn,'P',$p_province); ?></option>
                       </select>
                   </div>
                    
                   <div class="input-group mt-3">
                        <div class="form-floating">
                        <select name="p_muncity" id="p_muncity" class="form-select">
                              <?php
                              $muncity = fetchAddress($conn,'C',$p_province);
                              foreach ($muncity as $key => $mc){ ?>
                                  <option value="<?php echo $mc['citymunCode']; ?>"><?php echo $mc['citymun_nm']; ?></option>
                              <?php } ?>
                       </select>
                       <label for="p_muncity">Municipality / City</label>
                        </div><button class="btn btn-primary"> Next <bi class="bi-caret-right"></bi></button>
                   </div>
                </form>
           <?php
                    break;
                    case 2:
                      $p2_fname = cleanstr($_GET['p_fname']);
                      $p2_lname = cleanstr($_GET['p_lname']);
                      $p2_mname = cleanstr($_GET['p_mname']);
                      $p2_address_1 = cleanstr($_GET['p_address_1']);
                      $p2_province = cleanstr($_GET['p_province']); 
                      $p2_province = cleanstr($_GET['p_muncity']); 
                ?>
                <form action="index.php" method="get">
                   <input type="number" value="3" name="done" hidden>
                   <div class="input-group">
                    <input required type="text" class="form-control" name="p_fname" id="p_fname" value="<?php echo $p_fname; ?>">     
                    <input required type="text" class="form-control" name="p_mname" id="p_mname" value="<?php echo $p_mname; ?>">     
                    <input required type="text" class="form-control" name="p_lname" id="p_lname" value="<?php echo $p_lname; ?>">     
                   </div>
                   <div class="input-group mt-3">
                       <input readonly type="text" name="p_address_1" id="p_address_1" value="<?php echo $p_address_1; ?>" class="form-control">     
                       <select disabled name="p_province" id="p_province" class="form-select">
                             <option selected value="<?php echo $p_province; ?>"><?php echo getAddressDesc($conn,'P',$p_province); ?></option>
                       </select>
                   </div>
                    
                   <div class="input-group mt-3">
                        <div class="form-floating">
                        <select name="p_muncity" id="p_muncity" class="form-select">
                              <option value="<?php echo $p_muncity; ?>" selected><?php echo getAddressDesc($conn,'C',$p_muncity);?> </option>
                       </select>
                       <label for="p_muncity">Municipality / City</label>
                        </div><button class="btn btn-primary"> Next <bi class="bi-caret-right"></bi></button>
                   </div>
                </form>
                <?php
                    break;
                }
            }?>
        </div>
        
    </div>
  </div>    
</div>

</body>
<script src="../js/bootstrap.min.js"></script>
</html>