<?php
if(isset($_GET['cartid'])){
        include "db_conn.php";
        $cart_id = htmlentities($_GET['cartid']);
         $sql_del = "DELETE FROM `cart` WHERE cart_id = ? ; ";
        $stmt_del = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt_del, $sql_del)){
            header("location: ../index.php?error=9"); //delete failed
            exit();
            }
        mysqli_stmt_bind_param($stmt_del,"s",$cart_id);
        mysqli_stmt_execute($stmt_del);
        header("location: ../index.php?success_delete");
        
    }