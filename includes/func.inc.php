<?php
function cleanstr($str){
    return htmlentities($str);
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
function fetchAddress($conn,$addressLevel,$param){
if($param == "1"){
    switch($addressLevel){
        case 'B': $sql = "SELECT b.brgyCode
                               , b.brgyDesc brgy_nm
                               , c.citymunCode
                               , c.citymunDesc citymun_nm
                               , p.provCode
                               , p.provDesc prov_nm
                            FROM `refbrgy` b
                            join `refcitymun` c 
                              on (b.citymunCode = c.citymunCode)
                            join `refprovince` p 
                              on (p.provCode = c.provCode)
                            WHERE ?
                            ORDER BY b.brgyDesc ASC; ";
        break;
        case 'C': $sql = "SELECT c.citymunCode
                               , c.citymunDesc citymun_nm
                               , p.provCode
                               , p.provDesc prov_nm
                            FROM `refcitymun` c 
                            join `refprovince` p 
                              on (c.provCode = c.provCode)
                            WHERE  ?
                            ORDER BY c.citymunDesc ASC; ";
        break;
        case 'P': $sql = "SELECT p.provCode
                               , p.provDesc prov_nm
                            FROM `refprovince` p
                            WHERE ?
                            ORDER BY p.provDesc ASC;";
        break;
    }
} else {
    switch($addressLevel){
        case 'B': $sql = "SELECT b.brgyCode
                               , b.brgyDesc brgy_nm
                               , c.citymunCode
                               , c.citymunDesc citymun_nm
                               , p.provCode
                               , p.provDesc prov_nm
                            FROM `refbrgy` b
                            join `refcitymun` c 
                              on (b.citymunCode = c.citymunCode)
                            join `refprovince` p 
                              on (p.provCode = c.provCode)
                            WHERE c.citymunCode = ?
                            ORDER BY b.brgyDesc ASC; ";
        break;
        case 'C': $sql = "SELECT c.citymunCode
                               , c.citymunDesc citymun_nm
                               , p.provCode
                               , p.provDesc prov_nm
                            FROM `refcitymun` c 
                            join `refprovince` p 
                              on (c.provCode = p.provCode)
                           WHERE p.provCode = ?
                           ORDER BY c.citymunDesc ASC; ";
        break;
        case 'P': $sql = "SELECT p.provCode
                               , p.provDesc prov_nm
                            FROM `refprovince` p
                            WHERE ?
                            ORDER BY p.provDesc ASC;";
        break;
    }
}
    
    
    $stmt=mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location:index.php?error=stmtfailed");
        exit();
    }
    
        mysqli_stmt_bind_param($stmt, "s" , $param);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        $arr = array();            //initialize an empty array
        while($row = mysqli_fetch_assoc($resultData)){
            array_push($arr,$row);            
        }
        return $arr;               //this is the return value
        mysqli_stmt_close($stmt);  //close the mysqli_statement
}

function getAddressDesc($conn, $level, $param){
    switch($level){
        case 'B': $sql = "SELECT brgyDesc FROM `refbrgy` WHERE brgyCode = ?;"; break;
        case 'C': $sql = "SELECT citymunDesc FROM `refcitymun` WHERE citymunCode = ?;"; break;
        case 'P': $sql = "SELECT provDesc FROM `refprovince` WHERE provCode = ?;"; break;
            
    }
    $stmt=mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        $err=false;
        return $err;
        exit;
    }
        mysqli_stmt_bind_param($stmt, "s" ,$param);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        
        if($row = mysqli_fetch_assoc($resultData)){
            switch($level){
                case 'B':  return $row['brgyDesc']; break;
                case 'C':  return $row['citymunDesc']; break;
                case 'P':  return $row['provDesc']; break;
            }
        }
        else{
            $err=false;
            return $err;
        }
        mysql_stmt_close($stmt);
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
           WHERE ( `Username`= ? 
             OR `emailadd` = ? )
             AND `Password` = ?
          ;";
    $stmt=mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: index.php?error=stmtfailed");
        exit();
    }
        mysqli_stmt_bind_param($stmt, "sss" ,$username,$username,$password);
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


function getCartSummary($conn, $user_id){
    $sql_cart_list = "SELECT c.user_id
                           , sum(i.item_price * c.item_qty) total_price
                           , sum(c.item_qty) total_qty
                        FROM cart c
                        JOIN items i
                          ON c.item_id = i.item_id
                       WHERE c.user_id = ? 
                          AND c.status = 'P'
                    GROUP BY c.user_id; ";
                      $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        header("location: index.php?error=stmtfailed");
                        exit();
                    }
        mysqli_stmt_bind_param($stmt, "s" ,$user_id);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        $arr = array();            //initialize an empty array
        if($row = mysqli_fetch_assoc($resultData)){
            array_push($arr,$row);            
        }
        return $arr;               //this is the return value
        mysqli_stmt_close($stmt);  //close the mysqli_statement
}

