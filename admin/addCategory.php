<?php
if(isset($_POST['addCategory'])){
include_once "../includes/db_conn.php";
include_once "../includes/func.inc.php";
    $itemcat  = htmlentities($_POST['itemcategory']);
    $itemstat = htmlentities($_POST['itemstatus']);
    
    
    //file upload initialization------------------
    $filecheckstat = true;
    $image_temp_file = $_FILES["itemimagefile"]["tmp_name"];
    $baseitem_img = basename($_FILES["itemimagefile"]["name"]);
    $ext = strtolower(pathinfo($baseitem_img,PATHINFO_EXTENSION));
    $target_dir = '../images';
    $target_filename = strtolower($itemname).".".$ext; 
    
  
  $check = getimagesize($image_temp_file);
  $filecheckstat = $check !== false ? true : false;
  echo "<br>";
    
  $file_stat = checkImage($_FILES["itemimagefile"], $target_dir, $target_filename);
    $file_err_count=0;
    $error_msg = null;
    foreach($file_stat as $key => $stat){
        if($stat != ''){
            $error_msg .= ($file_err_count+1). ": ". $stat ."<br>";
            $file_err_count++;
        }
    }
    if($error_msg !== null){
        header("location: index.php?error={$error_msg}");
        exit();
    }
    //file upload initialization------------------
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
                  (`item_name`, `item_short_code`, `item_price`, `cat_id`, `item_status`,`item_img`) 
                   VALUES (?,?,?,?,?,?);";
        $stmt_ins = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt_ins, $sql_ins)){
        header("location: index.php?error=2"); //insert failed
        exit();
        }
        mysqli_stmt_bind_param($stmt_ins,"ssssss",$itemname,$itemsc,$itemPrice,$itemcat,$itemstat,$target_filename);
        mysqli_stmt_execute($stmt_ins);
                
        if(!$file_err_count){
             //upload file.
            if (move_uploaded_file($image_temp_file, $target_dir."/".$target_filename)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
              } else {
                header("location: index.php?error=99"); //file upload failed
                exit();
              }
            
             header("location: index.php?error=0&itemname={$itemname}"); 
        }
        
        exit();
    }
}
