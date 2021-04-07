<?php 
if(isset($_POST['signup_submit'])){
    $fullname =$_POST['UserName'];
    $username =$_POST['UserUid'];
    $email =$_POST['UserEmail'];
    $pwd =$_POST['UserPwd'];
    $pwd2 =$_POST['UserPwd2'];
require_once "db_conn.php";
require_once "functions.inc.php";
    
    if ( emptyInput($fullname,$username,$email,$pwd,$pwd2) !== false ){
        header ("location: ../signup.php?error=emptyinputs");
        exit();
    }
    else if ( invalidUid($username) !== false ){
        header ("location: ../signup.php?error=invalidusername");
        exit();
    }
    else if ( invalidEmail($email) !== false ){
        header ("location: ../signup.php?error=invalidemail");
        exit();
    }
    else if ( pwdMatch($pwd,$pwd2) !== false  ){
         header ("location: ../signup.php?error=pwdmismatch");
        exit();
    }
    else if ( uidExists($conn,$username,$email) !== false ) {  
        header ("location: ../signup.php?error=userexists");
       exit();
    }
    else{
        CreateUser($conn,$fullname,$username,$email,$pwd);
        header ("location: ../signup.php?error=none");
        exit();
    }
    
}
else{
    header("location: ../signup.php");
}