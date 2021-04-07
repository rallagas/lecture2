<?php
if(isset($_GET['item_id'])){ ?>
    <form action="?">
        <input type="number" hidden name="item_id" value="<?php echo $_GET['item_id']; ?>">
        input quantity <input type="number" name="order_qty">
    </form>
<?php 
}
?>