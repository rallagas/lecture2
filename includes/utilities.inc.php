<?php

  function query($conn, $sql, $params = array() ){
        $err = false;
        $stmt=mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql)){
            $str = NULL;
            $cnt_p = count($params);
            
            if($cnt_p > 0){
                foreach($params as $param){
                    $str .= "s";
                    
                }
                 mysqli_stmt_bind_param($stmt, "{$str}" , ...$params );
            }
            
            
            if(mysqli_stmt_execute($stmt)){
                 $resultData = mysqli_stmt_get_result($stmt);
                 if(!empty($resultData)){
                      $arr=array();
                      while($row = mysqli_fetch_assoc($resultData)){
                          array_push($arr,$row);
                      }
                      return $arr;
                 }
                  else{
                     return true;
                 }
            }
            else{
               return false;
            }
        }
        return false;
            
    }



  function insert($conn, $table, $fields = array()){
        
        if(count($fields)){
            $keys = array_keys($fields);
            $values = null;
            $x = 1;
                foreach($fields as $field){
                    $values .= "?";
                    if($x < count($fields)){
                        $values .= ', ';
                    }
                $x++;
                }
            
            $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";
           
           if(query($conn, $sql, $fields ) !== false){
               return true;
           }
        }
        return false;
    }
    
     function update($conn,$table, $fields, $filter = array(), $op = array() ){
        $set = '';
        $where = '';
        $x = 1;
        $new_fields = array();
        
        foreach($fields as $name => $value){
            array_push($new_fields, $value);
            $set .= " {$name} = ? ";
            if($x < count($fields)){
                $set .= ', ';
            }
            $x++;
        }
         $x = 0;
         if(!empty($filter)){
             $where = ' WHERE ';
             foreach($filter as $col => $f_val){
                 array_push($new_fields, $f_val);
                 $where .= " {$col} = ? ";
                 if($x < count($op) ){
                     $where .= $op[$x];
                 }
             $x++;
             }
        
         }
         
         
        
        $sql = "UPDATE {$table} SET {$set} {$where}; ";
        
        if(query($conn,$sql,$new_fields)){
               
               return true;
           }
        return false;
        
    }


function get($conn, $table, $where = array() ){
    return que($conn ,'SELECT' , $table, $where );
}
function del($conn, $table, $where = array() ){
    return que($conn ,'DELETE' , $table, $where );
}

function que($conn ,$action , $table, $where = array() ){
    //$action = 'S' 
    //$where = array('item_id', '=', 1);
    $where_condition = NULL;
    $value = NULL;
    if(!empty($where)){
        if(count($where) === 3 ){
            $column = $where[0];
            $op     = $where[1];
            $value  = $where[2];
            
            $operators = array('=','>','<','>=','<=');
            
            if(in_array($op, $operators)){
               $where_condition = " AND " . $column . " " . $op . " ? ";
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    
    switch($action){
            CASE 'SELECT': $sql = " SELECT * FROM " . $table . $where_condition; 
            break;
            CASE 'DELETE': $sql = " DELETE FROM " . $table . $where_condition;
            break;
    }
    
    
    $stmt=mysqli_stmt_init($conn);
     if (!mysqli_stmt_prepare($stmt, $sql)){
           return false;
     }
    
    if($value !== NULL){
        mysqli_stmt_bind_param($stmt, "s" , $value);
    }
    
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if(!empty($resultData)){
         $arr=array();
         while($row = mysqli_fetch_assoc($resultData)){
             array_push($arr,$row);
         }
         return $arr;
    }
    else{
        return false;
    }
}
