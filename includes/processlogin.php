<?php
include_once "db_conn.php";
include_once "func.inc.php";
if(isset($_POST['p_username']) && isset($_POST['p_password'])){
$p_un = htmlentities($_POST['p_username']);
$p_pw = htmlentities($_POST['p_password']);
$user_info = uidExists( $conn, $p_un , $p_pw );
    if( $user_info !== false) {
        session_start();
        $_SESSION['user_type'] = $user_info['usertype'];
        $_SESSION['user_id'] = $user_info['cust_ref_number'];
        echo $_SESSION['user_type'];
        if($_SESSION['user_type'] == 'C'){
            header("location: ../customer/");
        }
        else if($_SESSION['user_type'] == 'A'){
            header("location: ../admin/index.php?overview");
        }
    }
    else{
          header("location: ../?error=userpasswordcombinationNotFound");
    }
    
}
else{
    header("location: ../");
}
   
