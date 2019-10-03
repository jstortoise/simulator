<?php
	include('config.php');

    if ($_POST['action'] == 'Update') {
    	$data = $_POST['data'];

    	$update = array();

    	foreach ($data as $key => $value) {
    		if ($key == 'field_id' || $key == 'new_field') {
    			continue;
    		}

    		if ($key != 'type') {
    			$value = "'" . $value . "'";
    		}

    		array_push($update,$key . '=' . $value);
    	}

    	$result = mysqli_query($db, 'UPDATE fields SET ' . join(',', $update) . ' WHERE field_id = "' . $data['field_id'] . '"');
    	echo json_encode(array('success' => true));
    } else if ($_POST['action'] == 'updateformula') {
    	$fieldid = $_POST['field_id'];
    	$value = $_POST['formula'];

    	$query = mysqli_query($db, 'SELECT * FROM formulas WHERE field_id = "' . $fieldid . '"');
    	$result = mysqli_fetch_array($query);

    	if (!$value) {
    		mysqli_query($db, 'DELETE FROM formulas WHERE field_id = "' . $fieldid . '"');
    		echo json_encode(array('success' => true));
    	} else {
    		if ($result) {
	    		mysqli_query($db, 'UPDATE formulas SET formula="' . $value . '" WHERE field_id = "' . $fieldid . '"');
	    	} else {
	    		mysqli_query($db, 'INSERT INTO formulas(field_id,formula) VALUES("' . $fieldid . '","' . $value . '")');
	    	}

	    	echo json_encode(array('success' => true));
		}
	} else {
    	$data = $_POST['data'];
    	$field = array();
    	$value_array = array();

    	foreach ($data as $key => $value) {
    		array_push($field,$key);
    		if ($key == 'new_field') {
    			continue;
    		}
    		if ($key != 'type') {
    			$value = "'" . $value . "'";
    		}

    		array_push($value_array,$value);
    	}
 
    	$query = mysqli_query($db, 'INSERT INTO fields(' . join(',',$field) . ') VALUES(' . join(',',$value_array) . ')');

    	echo json_encode(array('success' => true));
    }

?>