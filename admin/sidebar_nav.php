<ul class="list-unstyled ps-0">
    <li class="mb-1">
        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
            Home
        </button>
        <div class="collapse show" id="home-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li><a href="index.php?overview" class="link-dark rounded">Overview</a></li>
                <li><a href="control_dashboard.php" class="link-dark rounded">Items</a></li>
            </ul>
        </div>
    </li>
    <?php if(isset($page)){
            if($page === 'items'){ ?>

    <li class="mb-1">
        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
            Category
        </button>
        <div class="collapse show" id="home-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <?php
                 $category_list = getCategories($conn);
                 if(!empty($category_list) || $category_list !== false){
                     foreach($category_list as $kc => $cat){ ?>
                <li><a href="index.php?cat_id=<?php echo $cat['cat_id'];?>&catname=<?php echo $cat['cat_desc'];?>" class="link-dark rounded"><?php echo $cat['cat_desc'];?></a></li>
                <?php }
                        
                 }
                ?>

            </ul>
        </div>
    </li>
    <?php   }
     } 
    ?>
    <li class="mb-1">
        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
            Dashboard
        </button>
        <div class="collapse show" id="dashboard-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li><a href="index.php?overview" class="link-dark rounded">Overview</a></li>
                <li><a href="salesdashboard2.php?timeframe=D" class="link-dark rounded">Daily</a></li>
                <li><a href="salesdashboard2.php?timeframe=W" class="link-dark rounded">Weekly</a></li>
                <li><a href="salesdashboard2.php?timeframe=M" class="link-dark rounded">Monthly</a></li>
                <li><a href="salesdashboard2.php?timeframe=A" class="link-dark rounded">Annually</a></li>
            </ul>
        </div>
    </li>
    <li class="mb-1">
        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
            Orders
        </button>
        <div class="collapse show" id="orders-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li><a href="orders.php" class="link-dark rounded">All</a></li>
                <!--
                <li><a href="orders.php" class="link-dark rounded">Processed</a></li>
                <li><a href="orders.php" class="link-dark rounded">Shipped</a></li>
                <li><a href="orders.php" class="link-dark rounded">Returned</a></li>
-->
            </ul>
        </div>
    </li>
    <li class="border-top my-3"></li>
    <li class="mb-1">
        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
            Account
        </button>
        <div class="collapse" id="account-collapse">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                <li><a href="#" class="link-dark rounded">Profile</a></li>
                <li><a href="../includes/processlogout.php" class="link-dark rounded">Sign out</a></li>
            </ul>
        </div>
    </li>
</ul>
