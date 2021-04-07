<?php
echo "This is a test change";
if(isset($_POST['itemstatus'])){
include_once "includes/db_conn.php";
    $itemname = htmlentities($_POST['itemname']);
    $itemsc   = htmlentities($_POST['itemshortcode']);
    $itemPrice = htmlentities($_POST['itemprice']);
    $itemcat  = htmlentities($_POST['itemcategory']);
    $itemstat = htmlentities($_POST['itemstatus']);
    
    $sql_check = "SELECT item_id 
                    FROM items
                   WHERE item_name = ?
                     OR  item_short_code = ?;";
    $stmt_chk = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_chk, $sql_check)){
        header("location: index.php?error=3"); //statement failed
        exit();
    }
    mysqli_stmt_bind_param($stmt_chk,"ss",$itemname,$itemsc);
    mysqli_stmt_execute($stmt_chk);
    $chk_result=mysqli_stmt_get_result($stmt_chk);
    $arr=array();
    while($row = mysqli_fetch_assoc($chk_result)){
        array_push($arr,$row);
    }
    if(!empty($arr)){
        header("location: index.php?error=1&itemname={$itemname}"); //item exist
        exit();
    }
    else{
        $sql_ins = "INSERT INTO `items`
                  (`item_name`, `item_short_code`, `item_price`, `cat_id`, `item_status`) 
                   VALUES (?,?,?,?,?);";
        $stmt_ins = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt_ins, $sql_ins)){
        header("location: index.php?error=2"); //insert failed
        exit();
        }
        mysqli_stmt_bind_param($stmt_ins,"sssss",$itemname,$itemsc,$itemPrice,$itemcat,$itemstat);
        mysqli_stmt_execute($stmt_ins);
        header("location: index.php?error=0&itemname={$itemname}"); //successful
        exit();
    }
}
