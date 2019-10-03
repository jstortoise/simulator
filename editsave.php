<?php
	include('config.php');
    // $id_edit = $_POST['id']; 
    // $name_edit = $_POST['name_edit']; 
    // $value_edit = $_POST['value_edit'];
    // $unit_edit = $_POST['unit_edit'];
    // $type_edit = $_POST['type_edit'];
    // $description_edit = $_POST['description_edit'];
    // $sql = "UPDATE fields SET field_label='$name_edit', value='$value_edit', unit='$unit_edit', type='$type_edit', description='$description_edit' WHERE id = '$id_edit'";
    // $data_add = mysqli_query($db, $sql);

    if($_POST['action'] == 'Update')
    {
    	$data = $_POST['data'];

    	$update = array();

    	foreach($data as $key => $value)
    	{
    		if($key == 'field_id' || $key == 'defaultfield')
    		{
    			continue;
    		}

    		if($key != 'type')
    		{
    			$value = "'" . $value . "'";
    		}

    		array_push($update,$key . '=' . $value);
    	}

    	$result = mysqli_query($db,'update fields set ' . join(',',$update) . ' where field_id = "' . $data['field_id'] . '"');
    	echo json_encode(array('success'=>true));
    }
    else if($_POST['action'] == 'updateformula')
    {
    	$fieldid = $_POST['field_id'];
    	$value = $_POST['formula'];

    	$query = mysqli_query($db,'Select * from formulas where field_id = "' . $fieldid . '"');
    	$result = mysqli_fetch_array($query);

    	if(!$value)
    	{
    		mysqli_query($db,'Delete from formulas where field_id = "' . $fieldid . '"');
    		echo json_encode(array('success'=>true));
    	}
    	else
    	{
    		if($result)
	    	{
	    		mysqli_query($db,'update formulas set formula="' . $value . '" where field_id = "' . $fieldid . '"');
	    	}
	    	else
	    	{
	    		mysqli_query($db,'insert into formulas(field_id,formula) Values("' . $fieldid . '","' . $value . '")');
	    	}

	    	echo json_encode(array('success'=>true));
    	}
    	

    	
    }
    else 
    {
    	$data = $_POST['data'];
    	$field = array();
    	$value_array = array();

    	foreach ($data as $key => $value) {
    		array_push($field,$key);
    		if($key == 'defaultfield')
    		{
    			continue;
    		}
    		if($key != 'type')
    		{
    			$value = "'" . $value . "'";
    		}

    		array_push($value_array,$value);
    	}
 
    	$query = mysqli_query($db,'insert into fields(' . join(',',$field) . ') Values(' . join(',',$value_array) . ')');

    	echo json_encode(array('success'=>true));
    }

?>