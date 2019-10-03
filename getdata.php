<?php 
	include('config.php');

	if (isset($_POST['action']) && $_POST['action'] == 'autofieldid') {
		$result = mysqli_query($db, 'SELECT * FROM fields WHERE id = (SELECT MAX(id) FROM fields)');
		$result_data =  mysqli_fetch_array($result);

		if ($result_data['new_field']) {
			echo json_encode(array('fieldid' => 'f35'));
		} else {
			$fieldid = $result_data['field_id'];
			$fieldvalue = preg_split('/f/', $fieldid)[1] + 1;
			echo json_encode(array('fieldid' => 'f' . $fieldvalue));
		}
	} else if(isset($_POST['action']) && $_POST['action'] == 'getformular') {
		$fieldid = $_POST['fieldid'];
		$result_data = mysqli_query($db,'SELECT * FROM formulas WHERE field_id = "' . $fieldid . '"');
		echo json_encode(mysqli_fetch_array($result_data));
	} else {
		$id = $_POST['edit_id'];
		$query = mysqli_query($db,'SELECT * FROM fields WHERE id = ' . $id);
		$result = mysqli_fetch_array($query, MYSQLI_ASSOC);
		echo json_encode($result);	
	}
?>