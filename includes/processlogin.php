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
        $_SESSION['user_id'] = $user_info['uid'];
        
        echo $_SESSION['user_type'];
        echo $_SESSION['user_id'];
        
        
         
    }
    
}
   