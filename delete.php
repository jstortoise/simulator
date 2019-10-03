<?php
	include('config.php');
    $id = $_POST['delete_id']; 
    $sql = "DELETE from fields where id = '$id'";
    $data_add = mysqli_query($db, $sql);

?>