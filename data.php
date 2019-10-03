<?php
	include('config.php');
    $name_add = $_POST['name_add']; 
    $value_add = $_POST['value_add'];
    $unit_add = $_POST['unit_add'];
    $type_add = $_POST['type_add'];
    $description_add = $_POST['description_add'];
    $sql = "INSERT INTO fields (field_label, value, unit, type, description) VALUES ('$name_add', '$value_add', '$unit_add', '$type_add', '$description_add')";
    $data_add = mysqli_query($db, $sql);
?>