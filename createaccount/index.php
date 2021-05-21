<?php
include_once "../includes/db_conn.php"; 
include_once "../includes/func.inc.php";
//included changes
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

    <div class="container my-4">
        <div class="row">
            <div class="col-md-12">
                <span class="btn"><a class="text-secondary" href="../index.php">Home</a> / Create an Account</span>
                <div class="shadow px-3 py-3">

                    <form action="create_account2.php" method="POST">
                        <div class="card-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-4">
                                        <div class="form-floating">
                                            <input required type="text" name="p_fname" id="p_fname" class="form-control">
                                            <label for="p_fname">First Name</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4">
                                        <div class="form-floating">
                                            <input required type="text" name="p_lname" id="p_lname" class="form-control">
                                            <label for="p_lname">Last Name</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4">
                                        <div class="form-floating">
                                            <input type="text" name="p_mname" id="p_mname" class="form-control">
                                            <label for="p_mname">Middle Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input required type="text" name="p_address_1" id="p_address_1" class="form-control">
                                            <label for="p_address_1">Address 1</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <select required name="p_province" id="p_province" class="form-select">
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
                                <button class="btn btn-primary" name="create_account_1"> Next <bi class="bi-caret-right"></bi></button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
<script src="../js/bootstrap.min.js"></script>

</html>
