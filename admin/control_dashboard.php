<?php
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
    <link rel="stylesheet" href="../font/bootstrap-icons.css">
    <link href="../css/sidebars.css" rel="stylesheet">

    <title>Sidebars · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/">
    <!-- Bootstrap core CSS -->

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main>
                <div class="flex-shrink-0 p-3 bg-white col-2">
                    <a href="/" class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
                        <span class="fs-5 fw-semibold">Control Dashboard</span>
                    </a>
                    <?php include_once "sidebar_nav.php";?>
                </div>
                <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white col-3 mt-2 px-4 shadow">

                    <form action="">
                        <div class="input-group w-100">
                            <input type="text" class="form-control" name="search_key">
                            <button class="btn btn-secondary"><i class="bi bi-search"></i></button>
                        </div>

                    </form>
                    <div class="list-group list-group-flush border-bottom scrollarea">
                        <?php 
                        if(isset($_GET['search_key'])){
                            $str = cleanstr($_GET['search_key']);
                            $items = showMenu($conn,null,$str);
                        }else{
                            $items = showMenu($conn);
                        }
                            
                            foreach($items as $ki => $item) { ?>

                        <span class="list-group-item list-group-item-action  py-3 lh-tight" aria-current="true">
                            <div class="d-flex w-100 align-items-center justify-content-between">

                                <strong class="mb-1">
                                    <img src="../images/<?php echo $item['item_img'];?>" alt="" class="border-0 rounded-circle" style="height:40px; width: 40px">
                                    <?php echo $item['item_name'];?>
                                    <span class="badge rounded-pill bg-secondary"><?php echo nf2($item['item_price']);?></span>

                                </strong>
                                <small><?php echo $item['item_short_code'];?></small>
                            </div>
                        </span>

                        <?php }
            ?>

                    </div>
                </div>
            </main>
        </div>
    </div>


    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../css/sidebars.js"></script>
</body>

</html>
