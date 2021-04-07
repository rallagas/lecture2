<?php
function displayCat($conn,$bycolumn = "all", $string =""){
    switch($bycolumn){
        case 'all':
             $sql = "SELECT * FROM `cate`;";
             break;
    }
    
}

function displayItemInfo($conn,$bycolumn = "all", $value = "" ){
    switch($bycolumn){
        case 'all': 
                 $sql = "SELECT i.item_id item_id
                              , c.cat_desc cat_desc
                              , i.item_short_code item_short_code
                              , i.item_name item_name
                              , i.item_price item_price
                           FROM `items` as i
                           JOIN `category` as c
                             ON i.cat_id = c.cat_id
                             where ?";
                 $value = 1;
        break;
        case 'exact_name': 
                 $sql = "SELECT i.item_id item_id
                              , c.cat_desc cat_desc
                              , i.item_short_code item_short_code
                              , i.item_name item_name
                              , i.item_price item_price
                           FROM `items` as i
                           JOIN `category` as c
                             ON i.cat_id = c.cat_id
                          WHERE I.item_name = ?;";
                 $value = $value;
        break;
        case 'like_name': 
                 $sql = "SELECT i.item_id item_id
                              , c.cat_desc cat_desc
                              , i.item_short_code item_short_code
                              , i.item_name item_name
                              , i.item_price item_price
                           FROM `items` as i
                           JOIN `category` as c
                             ON i.cat_id = c.cat_id
                          WHERE i.item_name like ?;";
                 $value = "%{$value}%";
        break;
        default: $sql = "SELECT i.item_id item_id
                              , c.cat_desc cat_desc
                              , i.item_short_code item_short_code
                              , i.item_name item_name
                              , i.item_price item_price
                           FROM `items` as i
                           JOIN `category` as c
                             ON i.cat_id = c.cat_id
                           WHERE ? ;";
                 $value = "";
        break;
    }
    
    $stmt=mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location:index.php?error=stmtfailed");
        exit();
    }
        mysqli_stmt_bind_param($stmt, "s" , $value);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        $arr = array();
        while($row = mysqli_fetch_assoc($resultData)){
            array_push($arr,$row);            
        }
        return $arr;
        mysqli_stmt_close($stmt); 
}


function fullDisplay($conn){
    $sql = "SELECT i.item_id item_id
                 , c.cat_desc cat_desc
                 , i.item_short_code item_short_code
                 , i.item_name item_name
                 , i.item_price item_price
              FROM `items` as i
              JOIN `category` as c
                ON i.cat_id = c.cat_id;";
    $stmt=mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location:index.php?error=stmtfailed");
        exit();
    }
    
      // mysqli_stmt_bind_param($stmt, "s" , $value);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        $arr = array();            //initialize an empty array
        while($row = mysqli_fetch_assoc($resultData)){
            array_push($arr,$row);            
        }
        return $arr;               //this is the return value
        mysqli_stmt_close($stmt);  //close the mysqli_statement
}


function createUser($conn,$username,$password,$usertype){
    $err;
    $sql="INSERT INTO `users` (`Username`,`Password`,`UserType`)
          VALUES (?,?,?) ;";

    $stmt=mysqli_stmt_init($conn);
    //check if statement is valid
     if (!mysqli_stmt_prepare($stmt, $sql)){
        return false;
        exit();
     }
        mysqli_stmt_bind_param($stmt, "sss" ,$username,$password,$usertype);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    
}


function uidExists($conn, $username, $password){
    $err;
    $sql="SELECT * FROM `users` 
           WHERE `Username`= ? 
             AND `Password` = ?
          ;";
    $stmt=mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: index.php?error=stmtfailed");
        exit();
    }
        mysqli_stmt_bind_param($stmt, "ss" ,$username,$password);
        mysqli_stmt_execute($stmt);
        
        $resultData = mysqli_stmt_get_result($stmt);
        
        if($row = mysqli_fetch_assoc($resultData)){
            return $row;
        }
        else{
            $err=false;
            return $err;
        }
        mysql_stmt_close($stmt);
}