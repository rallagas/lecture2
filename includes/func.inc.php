<?php
function cleanstr($str){
    return htmlentities($str);
}

function setisEmpty(){
   $bool_empty = false;
   $args = func_get_args();
     for($i = 0; $i < func_num_args(); $i++){
        if($args[$i] == "" ){
            $bool_empty = true;
            break;
        }     
     }
    return $bool_empty;
}

function displayItemInfo($conn,$bycolumn = "all", $value = "", $cat = array() ){
    if( sizeof($cat) < 1 ) {
            switch($bycolumn){
            case 'all': 
                     $sql = "SELECT i.item_id item_id
                                  , i.item_img
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
                                  , i.item_img
                                  , c.cat_desc cat_desc
                                  , i.item_short_code item_short_code
                                  , i.item_name item_name
                                  , i.item_price item_price
                               FROM `items` as i
                               JOIN `category` as c
                                 ON i.cat_id = c.cat_id
                              WHERE I.item_name = ?
                                AND ? ;";
                     $value = $value;
            break;
            case 'like_name': 
                     $sql = "SELECT i.item_id item_id
                                  , i.item_img
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
                                  , i.item_img
                                  , c.cat_desc cat_desc
                                  , i.item_short_code item_short_code
                                  , i.item_name item_name
                                  , i.item_price item_price
                               FROM `items` as i
                               JOIN `category` as c
                                 ON i.cat_id = c.cat_id
                               WHERE 1 = ?;";
                    $value = 1;
            break;
        }
    }
    else{
        $cat_filter = "0";
        if(sizeof($cat) > 1){
           foreach($cat as $cat_id){
            $cat_filter .= ", '{$cat_id}'";
           }
        }
        else{
            $cat_filter = $cat[0];
        }
            switch($bycolumn){
            case 'all': 
                     $sql = "SELECT i.item_id item_id
                                  , i.item_img
                                  , c.cat_desc cat_desc
                                  , i.item_short_code item_short_code
                                  , i.item_name item_name
                                  , i.item_price item_price
                               FROM `items` as i
                               JOIN `category` as c
                                 ON i.cat_id = c.cat_id
                                 WHERE 1 = ?
                                   and i.cat_id in ( {$cat_filter} )";
                     $value = 1;
            break;
            case 'exact_name': 
                     $sql = "SELECT i.item_id item_id
                                  , i.item_img
                                  , c.cat_desc cat_desc
                                  , i.item_short_code item_short_code
                                  , i.item_name item_name
                                  , i.item_price item_price
                               FROM `items` as i
                               JOIN `category` as c
                                 ON i.cat_id = c.cat_id
                              WHERE I.item_name = ?
                                AND i.cat_id in ( {$cat_filter} );";
                     $value = $value;
            break;
            case 'like_name': 
                     $sql = "SELECT i.item_id item_id
                                  , i.item_img
                                  , c.cat_desc cat_desc
                                  , i.item_short_code item_short_code
                                  , i.item_name item_name
                                  , i.item_price item_price
                               FROM `items` as i
                               JOIN `category` as c
                                 ON i.cat_id = c.cat_id
                              WHERE i.item_name like ?
                                AND i.cat_id in ( {$cat_filter} ) ;";
                     $value = "%{$value}%";
            break;
            default: $sql = "SELECT i.item_id item_id
                                  , i.item_img
                                  , c.cat_desc cat_desc
                                  , i.item_short_code item_short_code
                                  , i.item_name item_name
                                  , i.item_price item_price
                               FROM `items` as i
                               JOIN `category` as c
                                 ON i.cat_id = c.cat_id
                               WHERE 1 = ? 
                                 AND i.cat_id in ( {$cat_filter} );";
                     $value = 1;
            break;
        }
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
                 , i.item_img item_img
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
if($param == "1"){ //This means ALL
    switch($addressLevel){
        case 'B': $sql = "SELECT DISTINCT b.brgyCode
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
        case 'C': $sql = "SELECT DISTINCT c.citymunCode
                               , c.citymunDesc citymun_nm
                               , p.provCode
                               , p.provDesc prov_nm
                            FROM `refcitymun` c 
                            join `refprovince` p 
                              on (c.provCode = p.provCode)
                            WHERE  ?
                            ORDER BY c.citymunDesc ASC; ";
        break;
        case 'P': $sql = "SELECT DISTINCT p.provCode
                               , p.provDesc prov_nm
                            FROM `refprovince` p
                            WHERE ?
                            ORDER BY p.provDesc ASC;";
        break;
    }
} else {
    switch($addressLevel){
        case 'B': $sql = "SELECT DISTINCT b.brgyCode
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
        case 'C': $sql = "SELECT DISTINCT c.citymunCode
                               , c.citymunDesc citymun_nm
                               , p.provCode
                               , p.provDesc prov_nm
                            FROM `refcitymun` c 
                            join `refprovince` p 
                              on (c.provCode = p.provCode)
                           WHERE p.provCode = ?
                           ORDER BY c.citymunDesc ASC; ";
        break;
        case 'P': $sql = "SELECT DISTINCT p.provCode
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
function createCustomer($conn,$cust_ref_num,$p_username, $p_password, $email,$firstname,$lastname,$midname,$address1,$brgy,$cityMun,$province,$zipcode, $gender ){
   $ok_stat = true;
   $sql_new_user = "INSERT INTO `users` (`cust_ref_number`,`username`,`password`,`emailadd`,`usertype`) VALUES (?,?,?,?,'C'); ";
    $stmt=mysqli_stmt_init($conn);
    //check if statement is valid
     if (!mysqli_stmt_prepare($stmt, $sql_new_user)){
        return false;
        exit();
     }
        mysqli_stmt_bind_param($stmt, "ssss" ,$cust_ref_num,$p_username, $p_password, $email );
        mysqli_stmt_execute($stmt);
   
 $sql_new_customer = "INSERT INTO `customer` (`cust_ref_number`, `cust_fname`, `cust_lname`, `cust_mname`, `cust_address_1`, `cust_address_brgy`, `cust_address_town`, `cust_address_province`, `cust_address_zipcode`, `cust_gender`, `cust_status`)  VALUES (?,?,?,?,?,?,?,?,?,?,'A'); ";
    $stmt1=mysqli_stmt_init($conn);
    //check if statement is valid
     if (!mysqli_stmt_prepare($stmt1, $sql_new_customer)){
        return false;
        exit();
     }
        mysqli_stmt_bind_param($stmt1, "ssssssssss",$cust_ref_num,$firstname,$lastname,$midname,$address1,$brgy,$cityMun,$province,$zipcode, $gender );
        mysqli_stmt_execute($stmt1);
        
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmt1);
   
return $ok_stat;
}
function createUser($conn,$username,$password, $email,$usertype){
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


function get_random_figures($str){
    $date_obj = date_create(); 
    $reg_ref_num = date_timestamp_get($date_obj) . random_int(10000,99999) . bin2hex($str);
    return $reg_ref_num;
}

function userNameExists($conn, $username){
    $err;
    $sql="SELECT * FROM `users` 
           WHERE `username` = ? 
           and `usertype` = 'C'
          ;";
    $stmt=mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        return false;
        exit();
    }
        mysqli_stmt_bind_param($stmt, "s" ,$username);
        mysqli_stmt_execute($stmt);
        
        $resultData = mysqli_stmt_get_result($stmt);
        
        if($row = mysqli_fetch_assoc($resultData)){
            return true;
        }
        else{
            return false;
        }
        mysql_stmt_close($stmt);
}

function getCartCount($conn,$user){
    $sql_cart_count = "SELECT COUNT(*) cartcount FROM `cart` WHERE status = 'P' AND user_id = ?;";
    $stmt=mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql_cart_count)){
    header("location: ?error=stmtfailed");
    exit();
}
    mysqli_stmt_bind_param($stmt, "s" ,$user);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
if(!empty($resultData)){
    if($row = mysqli_fetch_assoc($resultData)){
      return $row['cartcount'];
    }
}else{
    return 0;
}

}

function uidExists($conn, $username, $password){
    $err;
    $sql="SELECT * FROM `users` 
           WHERE ( `username`= ? 
             OR `emailadd` = ? )
             AND `password` = ?
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

function getCartItems($conn, $userid){
     $sql_cart_list = "SELECT c.cart_id
                            , i.item_name
                            , i.item_img
                            , i.item_price
                            , c.item_qty
                            , c.user_id
                         FROM cart c
                         JOIN items i
                           ON c.item_id = i.item_id
                        WHERE c.user_id = ? 
                           AND c.status = 'P'; ";
                      $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        return false;
                        exit();
                    }
                        mysqli_stmt_bind_param($stmt, "s" ,$userid);
                        mysqli_stmt_execute($stmt);

                        $resultData = mysqli_stmt_get_result($stmt);
                        if(!empty($resultData)){
                            $arr = array();
                            while($row = mysqli_fetch_assoc($resultData)){ 
                                array_push($arr,$row);
                            }
                        return $arr;
                        }else{
                            return false;
                        }
                       
}

function getCategories($conn){
    $sql = "SELECT * FROM `category`";
    $stmt=mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        return false;
        exit;
    }
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        $resArr = array();
      if(!empty($resultData)){
        while($row = mysqli_fetch_assoc($resultData)){
            array_push($resArr, $row);
        }
        return $resArr;
      }
        else{
            return false;
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

