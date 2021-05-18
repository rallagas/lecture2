<?php
$page='items';
session_start();
include_once "../includes/db_conn.php";
include_once "../includes/utilities.inc.php";
include_once "../includes/func.inc.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>Lecture : SQL Integration with PHP</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/sidebars.css">
    <link rel="stylesheet" href="../font/bootstrap-icons.css">
    <link href="../css/sidebars.css" rel="stylesheet">

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/">
    <!-- Bootstrap core CSS -->

</head>

<body>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="flex-shrink-0 p-3 bg-white  col-2">
                    <a href="/" class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
                        <svg class="bi me-2" width="30" height="24">
                            <use xlink:href="#bootstrap" />
                        </svg>
                        <span class="fs-5 fw-semibold">Items</span>
                    </a>
                    <?php include_once "sidebar_nav.php";?>
                </div>
                <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-light col-10">
                    <?php
                        if(isset($_GET['cat_id']) && isset($_GET['catname'])){ ?>

                    <span class="d-flex align-items-center flex-shrink-0 p-3 link-dark text-decoration-none border-bottom">

                        <span class="fs-1 fw-semibold"><?php echo cleanstr($_GET['catname']); ?></span>

                    </span>


                    <div class="container-fluid">
                        <div class="row" id="stats">

                            <div class="col-3 p-0">
                                <div class="card border-0">
                                    <div class="card-header">
                                        <span class="fs-6 fw-light justify-content-center d-flex">Pending Orders</span>
                                    </div>
                                    <div class="card-body justify-content-center d-flex">
                                        <div style="height: 200px" class="align-middle justify-content-center d-flex w-50  rounded-3 p-3 border border-5 border-danger">
                                            <h1 class="display-1 text-dark mt-4"><?php echo getTotalsPerCat($conn, cleanstr($_GET['cat_id']) , 'P');?></h1>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 p-0">
                                <div class="card border-0">
                                    <div class="card-header">
                                        <span class="fs-6 fw-light justify-content-center d-flex">Confirmed Orders</span>
                                    </div>
                                    <div class="card-body justify-content-center d-flex">
                                        <div style="height: 200px" class="align-middle justify-content-center d-flex w-50 p-3 rounded-3 border border-5 border-warning">
                                            <h1 class="display-1 text-dark mt-4"><?php echo getTotalsPerCat($conn, cleanstr($_GET['cat_id']) , 'C');?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 p-0">
                                <div class="card border-0">
                                    <div class="card-header">
                                        <span class="fs-6 fw-light justify-content-center d-flex">Processed Orders</span>
                                    </div>
                                    <div class="card-body justify-content-center d-flex">
                                        <div style="height: 200px" class="align-middle justify-content-center d-flex w-50  rounded-3 p-3 border border-3 border-info">
                                            <h1 class="display-1 text-dark mt-4"><?php echo getTotalsPerCat($conn, cleanstr($_GET['cat_id']) , 'W');?></h1>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 p-0">
                                <div class="card border-0">
                                    <div class="card-header">
                                        <span class="fs-6 fw-light justify-content-center d-flex">Delivered Orders</span>
                                    </div>
                                    <div class="card-body justify-content-center d-flex">
                                        <div style="height: 200px" class="align-middle justify-content-center d-flex w-50  rounded-3 p-3 border border-3 border-success">
                                            <h1 class="display-1 text-dark mt-4"><?php echo getTotalsPerCat($conn, cleanstr($_GET['cat_id']) , 'X');?></h1>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php }
                    if(isset($_GET['overview'])){ ?>

                    <span class="fs-1 fw-semibold">Overview</span>
                    <div class="container-fluid">
                        <div class="row" id="stats">

                            <div class="col-3 p-0">
                                <div class="card border-0">
                                    <div class="card-header">
                                        <span class="fs-6 fw-light justify-content-center d-flex">Pending Orders</span>
                                    </div>
                                    <div class="card-body justify-content-center d-flex">
                                        <div style="height: 200px" class="align-middle justify-content-center d-flex w-50  rounded-3 p-3 border border-5 border-danger">
                                            <h1 class="display-1 text-dark mt-4"><?php echo getTotalsPerStat($conn, 'P');?></h1>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 p-0">
                                <div class="card border-0">
                                    <div class="card-header">
                                        <span class="fs-6 fw-light justify-content-center d-flex">Confirmed Orders</span>
                                    </div>
                                    <div class="card-body justify-content-center d-flex">
                                        <div style="height: 200px" class="align-middle justify-content-center d-flex w-50 p-3 rounded-3 border border-5 border-warning">
                                            <h1 class="display-1 text-dark mt-4"><?php echo getTotalsPerStat($conn, 'C');?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 p-0">
                                <div class="card border-0">
                                    <div class="card-header">
                                        <span class="fs-6 fw-light justify-content-center d-flex">Processed Orders</span>
                                    </div>
                                    <div class="card-body justify-content-center d-flex">
                                        <div style="height: 200px" class="align-middle justify-content-center d-flex w-50  rounded-3 p-3 border border-3 border-info">
                                            <h1 class="display-1 text-dark mt-4"><?php echo getTotalsPerStat($conn, 'W');?></h1>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 p-0">
                                <div class="card border-0">
                                    <div class="card-header">
                                        <span class="fs-6 fw-light justify-content-center d-flex">Delivered Orders</span>
                                    </div>
                                    <div class="card-body justify-content-center d-flex">
                                        <div style="height: 200px" class="align-middle justify-content-center d-flex w-50  rounded-3 p-3 border border-3 border-success">
                                            <h1 class="display-1 text-dark mt-4"><?php echo getTotalsPerStat($conn, 'X');?></h1>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php }
                        ?>
                </div>

            </div>
        </div>




    </main>


    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../css/sidebars.js"></script>
</body>

</html>
