<?php
	include('config.php');
    $id = $_POST['delete_id']; 
    $sql = "DELETE FROM fields WHERE id = '$id'";
    $data_add = mysqli_query($db, $sql);
?>