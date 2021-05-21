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
    <link rel="stylesheet" href="../css/custom.css">
</head>

<body>
    <?php
if(isset($_POST['create_account_1'])){
    $firstname = htmlentities($_POST['p_fname']);
    $midname = htmlentities($_POST['p_mname']);
    $lastname = htmlentities($_POST['p_lname']);
    $address1 = htmlentities($_POST['p_address_1']);
    $province = htmlentities($_POST['p_province']);
    ?>
    <div class="container my-4">
        <div class="row">
            <div class="col-md-12">
                <span class="btn"><a class="text-secondary" href="../index.php">Home</a> / <a href="index.php" class="text-secondary">Previous</a> / Create an Account</span>
                <div class="shadow px-3 py-3">

                    <form action="create_account3.php" method="POST">

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
                                            <input type="text" name="p_lname" id="p_lname" value="<?php echo $lastname;?>" class="form-control text-secondary">
                                            <label for="p_lname">Last Name</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4">
                                        <div class="form-floating">
                                            <input type="text" name="p_mname" id="p_mname" value="<?php echo $midname;?>" class="form-control text-secondary">
                                            <label for="p_mname">Middle Name</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" name="p_address_1" id="p_address_1" value="<?php echo $address1;?>" class="form-control text-secondary">
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
                                            <select name="p_municipality" id="p_municipality" class="form-select text-primary shadow">
                                                <option>--SELECT CITY / MUNICIPALITY--</option>
                                                <?php
                                        $citymunList = fetchAddress($conn,'C',$province);
                                        foreach ($citymunList as $key => $cm){ ?>
                                                <option value="<?php echo $cm['citymunCode']; ?>"><?php echo $cm['citymun_nm']; ?></option>
                                                <?php } ?>

                                            </select>
                                            <label for="p_province">City / Municipality</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="d-flex flex-row-reverse">
                                <button class="btn btn-primary" name="create_account_2"> Next <bi class="bi-caret-right"></bi></button>
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
