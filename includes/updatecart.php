<?php
if(isset($_POST['cart_id'])){
        include "db_conn.php";
        $cart_id = htmlentities($_POST['cart_id']);
        $new_qty = htmlentities($_POST['item_qty']);
         $sql_upd = "UPDATE `cart`
                        SET item_qty = ?
                    WHERE cart_id = ?";
        $stmt_upd = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt_upd, $sql_upd)){
        header("location: ../index.php?error=8"); //update failed
        exit();
        }
        mysqli_stmt_bind_param($stmt_upd,"ss",$new_qty,$cart_id);
        mysqli_stmt_execute($stmt_upd);
        header("location: ../index.php?success_update=1");
        
    }