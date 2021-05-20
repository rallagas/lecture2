<?php
if(isset($_SESSION['user_type']) && isset($_SESSION['user_id'])){
    if($_SESSION['user_type'] == 'C'){
        header("location: ../customer/?error=cannotgothere");
    }
}
else{
    header("location: ../?error=cannotgothere");
}
