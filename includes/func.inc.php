<?php
function cleanstr($str){
    return htmlentities($str);
}
function checkImage($img_file, $targetdir, $targetimagename){
    
 $stat = array(
 'fileSizeOk' => '',
 'fileExists' => '',
 'fileType' => ''
 );
    
    $tmp_filename = $img_file['tmp_name'];
    $file_size = $img_file['size'];
    $img_size = getimagesize($img_file['tmp_name']);
    $img_mime = $img_size['mime'];
    $acceptable_files = array('image/jpeg','image/png','image/jpg');
    
    if(! in_array($img_mime, $acceptable_files)){
        $stat['fileType'] = "This file is not an Image .[jpg / png] only";
    }
    if($img_size === false || $file_size > 500000){
        $stat['fileSizeOk'] = "Image size is not acceptable [5MB below only]";
    }
    if(file_exists($targetdir."/".$targetimagename)){
        $stat['fileExists'] = "File Exists. Change the Item Name";
    }
    
    return $stat;
    
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
function nf2($amt){
    return "Php ". number_format($amt,2);
}
function pcpcs($amt){
    return ($amt > 1 ? ' pcs' : 'pc');
}
function showMenu($conn, $cat = null, $searchkey = null){
  if($searchkey === null){
       if($cat === null) {
            //declare the SQL
           $sql = "SELECT i.item_id
                     , i.minimum_qty
                     , i.item_name
                     , i.item_short_code
                     , c.cat_desc
                     , i.item_price
                     , i.item_img
                  FROM `items` i
                  JOIN `category` c
                    ON i.cat_id = c.cat_id ;";
        
        $stmt=mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
           return false;
           exit();
         }
      }
      else
      {  //check if $cat has value
            $sql = "SELECT i.item_id
                     , i.minimum_qty
                     , i.item_name
                     , i.item_short_code
                     , c.cat_desc
                     , i.item_price
                     , i.item_img
                  FROM `items` i
                  JOIN `category` c
                    ON i.cat_id = c.cat_id 
                WHERE c.cat_id = ?;";
        
        $stmt=mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
           return false;
           exit();
         }
        mysqli_stmt_bind_param($stmt, "s" , $cat);
      }
  }
  else{ //check if searchkey variable is not NULL
        $sql = "SELECT i.item_id
                     , i.minimum_qty
                     , i.item_name
                     , i.item_short_code
                     , c.cat_desc
                     , i.item_price
                     , i.item_img
                  FROM `items` i
                  JOIN `category` c
                    ON i.cat_id = c.cat_id
                 WHERE i.item_name LIKE ?
                    OR i.item_short_code = ?
                    OR c.cat_desc like ?
                    OR i.item_price = ?;";
        $stmt=mysqli_stmt_init($conn);
         if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "Something went wrong.";
            exit();
         }
        $itemname="%{$searchkey}%"; 
        mysqli_stmt_bind_param($stmt, "ssss" , $itemname, $searchkey, $itemname, $searchkey);
        
    }
         mysqli_stmt_execute($stmt);
    
       $resultData = mysqli_stmt_get_result($stmt);
    if(!empty($resultData)){
        $arr = array();
        while($row = mysqli_fetch_assoc($resultData)){
            array_push($arr,$row);
        }
        return $arr;
    }
    else{
        return false;
    }
        
}
function getSalesPerfCat($conn, $cat_id = null, $date = null){
if($date == null && $cat_id != null){
     $sql="SELECT c.date_ordered
               , sum(i.item_price * c.item_qty ) total_net_sale
               , count(c.item_id) total_item_ordered
            from `cart` c
            join `items` i
              on (c.item_id = i.item_id)
           WHERE i.cat_id = ?
             AND c.confirm = 'Y'
             AND c.status IN ('C','X')
             GROUP BY c.date_ordered 
             ORDER BY c.date_ordered DESC
             LIMIT 30;
    ";
    $params = array();
    array_push($params, $cat_id);
    
    return query($conn, $sql, $params );
} 
else if($date != null && $cat_id != null) {
        $sql="SELECT  c.date_ordered 
               , sum(i.item_price * c.item_qty ) total_net_sale
               , count(c.item_id) total_item_ordered
            from `cart` c
            join `items` i
              on (c.item_id = i.item_id)
           WHERE i.cat_id = ?
             AND c.date_ordered = ?
             AND c.confirm = 'Y'
             AND c.status IN ('C','X')
             GROUP BY c.date_ordered 
             ORDER BY c.date_ordered DESC
             LIMIT 30;
    ";
    $params = array();
    array_push($params, $cat_id, $date);
    
    return query($conn, $sql, $params );
}
else{
     $sql="SELECT ct.cat_desc
               , c.date_ordered 
               , sum(i.item_price * c.item_qty ) total_net_sale
               , count(c.item_id) total_item_ordered
            from `cart` c
            join `items` i
              on (c.item_id = i.item_id)
            join `category` ct
              on (i.cat_id = ct.cat_id)
           WHERE c.date_ordered > CURRENT_DATE - 31
             AND c.confirm = 'Y'
             AND c.status IN ('C','X')
             GROUP BY c.date_ordered , ct.cat_desc
             ORDER BY c.date_ordered DESC
             LIMIT 30;
    ";
    
    return query($conn, $sql);
}
   
    
}
function getSalesPerfItem($conn, $item_id = NULL, $date = array()){
    
    if($item_id == NULL){
        $sql="SELECT c.date_ordered
                    , ct.cat_desc
                    , i.item_name
                    , sum(i.item_price * c.item_qty ) total_net_sale
                    , count(c.item_id) total_item_ordered
                    from `cart` c
                    join `items` i
                    on (c.item_id = i.item_id)
                    JOIN `category` ct
                      ON (i.cat_id = ct.cat_id)
                    WHERE i.item_id = ?
                    AND c.confirm = 'Y'
                    AND c.status IN ('C','X')
                    GROUP BY i.item_name, c.date_ordered, ct.cat_desc
                   ORDER BY c.date_ordered DESC, i.cat_id ASC, i.item_name ASC
                   ;";
       $params = array();
       array_push($params, $item_id);
    }
    else{
 
        $datefilter=null;
        $params = array();
        switch(count($date)){
            case 1: 
                $datefilter=" AND c.date_ordered = ? ";
                array_push($params, $item_id, $date[0]);
                break;
            case 2: 
                $datefilter=" AND c.date_ordered between ? AND ? ";
                array_push($params, $item_id, $date[0], $date[1]);
                break;
            case 0: break;
            default: 
                $datefilter=" AND c.date_ordered IN ( ";
                $x=1;
                foreach($date as $d){ 
                   if($x === 1){
                      $datefilter .= "?";    
                   }else{
                       $datefilter .= ",?";
                   }
                   array_push($params,$item_id,$d);
                $x++;
                }
                $datefilter.= ") ";
                break;
        }
       
        $sql="SELECT c.date_ordered
                    , i.item_name
                    , ct.cat_desc
                    , sum(i.item_price * c.item_qty ) total_net_sale
                    , count(c.item_id) total_item_ordered
                    from `cart` c
                    join `items` i
                    on (c.item_id = i.item_id)
                    JOIN `category` ct
                      ON (i.cat_id = ct.cat_id)
                    WHERE i.item_id = ?
                    {$datefilter}
                    AND c.confirm = 'Y'
                    AND c.status IN ('C','X')
                    GROUP BY c.date_ordered, i.item_name , ct.cat_desc
                    ORDER BY c.date_ordered DESC, i.cat_id ASC, i.item_name ASC
                    ;";
    }
    
    return query($conn, $sql, $params);
}
function getTotalsPerCat($conn, $catid, $status) {
    
    $sql = "SELECT count(c.cart_id) cart_count
              FROM `cart` c
              JOIN `items` i
                ON (c.item_id = i.item_id)
             WHERE c.status in ( ? )
               AND i.cat_id = ?;
            ";
    $params = array($status, $catid);
    $st = query($conn, $sql, $params);
    foreach ($st  as $key => $val){
        return $val['cart_count'];
    }
        ;
    
}
function getTotalsPerStat($conn, $status) {
    
    $sql = "SELECT count(c.cart_id) cart_count
                
              FROM `cart` c
              JOIN `items` i
                ON (c.item_id = i.item_id)
             WHERE c.status in ( ? );
            ";
    $params = array($status);
    $st = query($conn, $sql, $params);
    foreach ($st  as $key => $val){
        return $val['cart_count'];
    }
        
    
}
function getTotals($conn, $time_param = null){
    
    if($time_param !== NULL){
            $params = array($time_param,$time_param,$time_param);
            return query(  $conn
                 , "SELECT sum(item_qty) item_qty
                              , sum(i.item_price * c.item_qty) sales
                           FROM `lu_day` t
                           JOIN `cart` c
                             on c.date_ordered = t.date
                           JOIN `items` i
                             on c.item_id = i.item_id
                          WHERE (t.year_id = ?
                             OR  t.date = ?
                             OR  t.week_id = ?
                            )
                          ;"
                 , $params);
    }else{
        return query(  $conn, "SELECT sum(item_qty) item_qty
                              , sum(i.item_price * c.item_qty) sales
                           FROM `lu_day` t
                           JOIN `cart` c
                             on c.date_ordered = t.date
                           JOIN `items` i
                             on c.item_id = i.item_id
                          ;" );
    }
    
    
}
function getRandom(){
    $r = null;
    $characters = array(1=>'A',
                        2=>'B',
                        3=>'C',
                        4=>'D',
                        5=>'E',
                        6=>'F',
                        7=>'G',
                        8=>'H',
                        9=>'I',
                        10=>'J',
                        11=>'K',
                        12=>'L',
                        13=>'M',
                        14=>'N',
                        15=>'O',
                        16=>'P',
                        17=>'Q',
                        18=>'R',
                        19=>'S',
                        20=>'T',
                        21=>'U',
                        22=>'V',
                        23=>'W',
                        24=>'X',
                        25=>'Y',
                        26=>'Z',
);
    $random_num = NULL;
    for ($i = 0; $i <= 5; $i++){
        $random_num .= rand($i, 99);
    }
 for($j = 0; $j < 5; $j++){
    foreach($characters as $key => $char){
    if($key == rand(1,26)){
        $r .= $char;
     }
    }
 }
    return substr($r . $random_num, 0 , 12);
}
function displayItemInfo($conn, $value = "", $category = array()){
    if(sizeof($category) > 0){
        $catStr = "0";
        foreach($category as $cat){
            $catStr .= "," . $cat;
        }
        $sql="SELECT * from items WHERE cat_id in ( {$catStr} ) AND item_name like ? ;";
    }else{
        $sql="SELECT * from items WHERE item_name like ? ;";
    }
    
    $stmt=mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location:index.php?error=stmtfailed");
        exit();
    }
    $value = "%{$value}%" ;
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
function getOrderList($conn, $status = null, $userid = NULL, $date_ordered = null){
if($userid === null){
    if($status !== null){
          $sql_cart_list = "SELECT c.order_ref_num
                           , c.status
                           , c.date_ordered
                           , c.last_update_date
                           , c.total_amt_to_pay
                           , sum(c.item_qty) total_item_qty
                        FROM cart c
                           , items i
                       WHERE c.item_id = i.item_id
                          AND c.status IN (?)
                          AND c.confirm = 'Y'
                          group by c.order_ref_num
                           , c.status
                           , c.date_ordered
                           , c.total_amt_to_pay
                           , c.last_update_date
                        ORDER BY c.date_ordered
                        LIMIT 60; ";
     $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        return false;
                        exit();
                    }
                        mysqli_stmt_bind_param($stmt, "s" ,$status);
    }
    else{
      $sql_cart_list = "SELECT c.order_ref_num
                           , c.status
                           , c.date_ordered
                           , c.total_amt_to_pay
                           , sum(c.item_qty) total_item_qty
                        FROM cart c
                           , items i
                       WHERE c.item_id = i.item_id
                          AND c.status IN ('C','X')
                          AND c.confirm = 'Y'
                          group by c.order_ref_num
                           , c.status
                           , c.date_ordered
                           , c.total_amt_to_pay
                        ORDER BY c.date_ordered ASC; ";
     $stmt=mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        return false;
                        exit();
                    }
    }
              
    
}
    else{
    if($status === null){
      $sql_cart_list = "SELECT c.order_ref_num
                           , c.status
                           , c.date_ordered
                           , c.total_amt_to_pay
                           , sum(c.item_qty) total_item_qty
                        FROM cart c
                           , items i
                       WHERE c.item_id = i.item_id
                          AND c.user_id = ? 
                          AND c.status IN ('C','X')
                          AND c.confirm = 'Y'
                          group by c.order_ref_num
                           , c.status
                           , c.date_ordered
                           , c.total_amt_to_pay
                        ORDER BY c.date_ordered; ";
     $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        return false;
                        exit();
                    }
                        mysqli_stmt_bind_param($stmt, "s" ,$userid);
    }
    else
    {
        
      $sql_cart_list = "SELECT c.order_ref_num
                           , c.status
                           , c.date_ordered
                           , c.total_amt_to_pay
                           , sum(c.item_qty) total_item_qty
                        FROM cart c
                           , items i
                       WHERE c.item_id = i.item_id
                          AND c.user_id = ? 
                          AND c.status IN ( ? )
                          AND c.confirm = 'Y'
                          group by c.order_ref_num
                           , c.status
                           , c.date_ordered
                           , c.total_amt_to_pay
                        ORDER BY c.date_ordered; ";
                  $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        return false;
                        exit();
                    }
                        mysqli_stmt_bind_param($stmt, "ss" ,$userid,$status);
    }
}

  
                     
                        mysqli_stmt_execute($stmt);

                        $resultData = mysqli_stmt_get_result($stmt);
    if(!empty($resultData)){
        $arr = array();
        while($row = mysqli_fetch_assoc($resultData)){
            array_push($arr,$row);
        }
        return $arr;
    }
    else{
        return false;
    }
    
}
function getCartList($conn, $param = null, $status = 'P'){
    if($param !== null){ //can be user or a order_ref_number
            $sql_cart_list = "SELECT i.item_id
                           , i.item_name
                           , i.item_img
                           , i.item_price
                           , c.status
                           , c.confirm
                           , sum(c.item_qty) total_item_qty
                           , sum(c.item_qty * i.item_price)  total_order_amt
                        FROM cart c
                        JOIN items i
                          ON c.item_id = i.item_id
                       WHERE ( c.user_id = ? 
                             or c.order_ref_num = ?
                             )
                          AND c.status = ?
                          group by i.item_name
                           , i.item_img
                           , i.item_price; ";
                      $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        return false;
                        exit();
                    }
                        mysqli_stmt_bind_param($stmt, "sss" ,$param,$param,$status);
    }else{
         $sql_cart_list = "SELECT i.item_id
                           , i.item_name
                           , i.item_img
                           , i.item_price
                           , c.status
                           , c.confirm
                           , sum(c.item_qty) total_item_qty
                           , sum(c.item_qty * i.item_price)  total_order_amt
                        FROM cart c
                        JOIN items i
                          ON c.item_id = i.item_id
                       WHERE group by i.item_name
                           , i.item_img
                           , i.item_price; ";
                      $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        return false;
                        exit();
                    }
        
    }
    
   
                        mysqli_stmt_execute($stmt);

                        $resultData = mysqli_stmt_get_result($stmt);
    if(!empty($resultData)){
        $arr = array();
        while($row = mysqli_fetch_assoc($resultData)){
            array_push($arr,$row);
        }
        return $arr;
    }
    else{
        return false;
    }
    
}
function getCheckedFees($conn){
    $sql_cart_list = "SELECT *
                        FROM checkout_standard_fees_cfg
                       WHERE CURRENT_DATE BETWEEN start_date_eff AND end_date_eff
                       AND status = 'A'
                        ORDER BY end_date_eff DESC
                        LIMIT 1;";
                      $stmt=mysqli_stmt_init($conn);
    
                    if (!mysqli_stmt_prepare($stmt, $sql_cart_list)){
                        return false;
                        exit();
                    }
                        //mysqli_stmt_bind_param($stmt, "s" ,$userid);
                        mysqli_stmt_execute($stmt);

                        $resultData = mysqli_stmt_get_result($stmt);
    if(!empty($resultData)){
        if($row = mysqli_fetch_assoc($resultData)){
           return $row; 
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}
function getCheckedOutList($conn, $userid){
    $sql_cart_list = "SELECT i.item_id
                           , i.item_name
                           , i.item_img
                           , i.item_price
                           , c.status
                           , c.confirm
                           , sum(c.item_qty) total_item_qty
                           , sum(c.item_qty * i.item_price)  total_order_amt
                        FROM cart c
                        JOIN items i
                          ON c.item_id = i.item_id
                       WHERE c.user_id = ? 
                          AND c.status = 'P'
                          AND c.confirm = 'Y'
                          group by i.item_name
                           , i.item_img
                           , i.item_price; ";
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
    }
    else{
        return false;
    }
    
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
function deleteCartItem($conn,$itemid,$userid){
    $err;
    $sql="DELETE FROM `cart` 
           WHERE user_id = ?
             and item_id = ?
             and status = 'P'";

    $stmt=mysqli_stmt_init($conn);
     if (!mysqli_stmt_prepare($stmt, $sql)){
        return false;
        exit();
     }
        mysqli_stmt_bind_param($stmt, "ss" ,$userid,$itemid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    
}
function placeOrder($conn, $userid, $ordernumber, $tatp=0){
$err;
$sql="UPDATE `cart`
SET status='C'
, order_ref_num = ?
, total_amt_to_pay = ?
WHERE user_id = ?
and status = 'P'
and confirm = 'Y';";

$stmt=mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)){
return false;
exit();
}
mysqli_stmt_bind_param($stmt, "sss" ,$ordernumber, $tatp ,$userid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
return true;

}
function confirmCartItem($conn,$itemid,$userid){
$err;
$sql="UPDATE `cart`
SET confirm='Y'
WHERE user_id = ?
and item_id = ?
and status = 'P'
and confirm = 'X'";

$stmt=mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)){
return false;
exit();
}
mysqli_stmt_bind_param($stmt, "ss" ,$userid,$itemid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
return true;

}
function unconfirmCartItem($conn,$itemid,$userid){
$err;
$sql="UPDATE `cart`
SET confirm='X'
WHERE user_id = ?
and item_id = ?
and status = 'P'
and confirm = 'Y'";

$stmt=mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)){
return false;
exit();
}
mysqli_stmt_bind_param($stmt, "ss" ,$userid,$itemid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
return true;

}
function updateCategory($conn,$cat_id,$new_cat_name){
$err;
$sql="";

$stmt=mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)){
return false;
exit();
}
mysqli_stmt_bind_param($stmt, "ss" ,$userid,$itemid);
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
function getUserFullName($conn,$user){
    $sql = "SELECT CASE WHEN u.usertype = 'C' then concat(c.cust_lname, ', ' , c.cust_fname, ' (@',u.username,')' )
                        WHEN u.usertype = 'A' then concat('Hello Admin, @',u.username, ' - ',u.emailadd) 
                   ELSE 'User Unrecognized' END as userinfo
              FROM `users` u
        LEFT OUTER JOIN `customer` c
                ON (c.cust_ref_number = u.cust_ref_number)
             WHERE u.cust_ref_number = ? ;
             ";
    $stmt=mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)){
    return false;
    exit();
}
    mysqli_stmt_bind_param($stmt, "s" ,$user);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if(!empty($resultData)){
        if($row = mysqli_fetch_assoc($resultData)){
          return $row['userinfo'];
        }
    }else{
        return false;
    }
mysql_stmt_close($stmt);
}
function getUserInfo($conn,$user){
    $sql = "SELECT c.*
              FROM `customer` c
            WHERE cust_ref_num = ?;
             ";
    $stmt=mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)){
    return false;
    exit();
}
    mysqli_stmt_bind_param($stmt, "s" ,$user);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if(!empty($resultData)){
        if($row = mysqli_fetch_assoc($resultData)){
          return $row;
        }
    }else{
        return false;
    }
mysql_stmt_close($stmt);
}
function getCartCount($conn,$user){
    $sql_cart_count = "SELECT count(distinct item_id) cartcount FROM `cart` WHERE status = 'P' AND user_id = ?;";
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
                          AND c.confirm = 'Y'
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
