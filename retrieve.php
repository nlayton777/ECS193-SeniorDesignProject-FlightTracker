<?php
    require_once 'login.php';

    $connection = new mysqli ($db_hostname, $db_username);
    if ($connection->connect_error) die($connection->connect_error);
    $connection->select_db("flight_tracker");

    if(isset($_GET) && isset($_GET['email']) && isset($_GET['id']))
    {
	$get = $_GET;
	$email = $get['email'];
	$id = $get['id'];
    } else{
	echo "_GET not set";
    }
    // for testing 
    $email = 'nicholasllayton@gmail.com';
    $id = 345;
    
    $query = <<<_QUERY
	SELECT *
	FROM `{$id}`
	ORDER BY opt_saletotal ASC;
_QUERY;
    $result = $connection->query($query);
    if (!$result)
    { die($connection->connect_error); }

    $str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
    $str .= "<xml>";

    $flag = true;
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $prevRow = $row;
    $nrows = $result->num_rows;
    for ($i = 0; $i < $nrows; ++$i)
    {
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	$optIdFlag = false;
	if ($prevRow['opt_id'] != $row['opt_id']) 
	{
	    // option 
	    $str .= "<option>";
	    $optIdFlag = true;
	}
		
		// option id
		$str .= "<id>{$row['opt_id']}</id>";
		// option saletotal
		$str .= "<saletotal>{$row['opt_saletotal']}</saletotal>";

	    $optSliceNumFlag = false;
	    if ($prevRow['opt_slice_num'] != $row['opt_slice_num'])
	    {
		// option slice
		$str .= "<slice>";
		$optSliceNumFlag = true;
	    }

		    // option slice number
		    $str .= "<num>{$row['opt_slice_num']}</num>";
		    
		$optSliceSegIdFlag = false;
		if ($prevRow['opt_slice_seg_id'] != $row['opt_slice_seg_id'])
		{
		    // option slice segment
		    $str .= "<segment>";
		    $optSliceSegIdFlag = true;
		}
			
			// option slice segment id
			$str .= "<id>{$row['opt_slice_seg_id']}</id>";
			// option slice segment duration
			$str .= "<duration>{$row['opt_slice_seg_duration']}</duration>";
			// option slice segment flight carrier
			$str .= "<carrier>{$row['opt_slice_seg_flight_carrier']}</carrier>";
			// option slice segment flight number
			$str .= "<number>{$row['opt_slice_seg_flight_num']}</number>";
			// option slice segment cabin
			$str .= "<cabin>{$row['opt_slice_seg_cabin']}</cabin>";

		    $optSliceSegLegIdFlag = false;
		    if ($prevRow['opt_slice_seg_leg_id'] != $row['opt_slice_seg_leg_id'])
		    {
			// option slice segment leg
			$str .= "<leg>";
			$optSliceSegLegIdFlag = true;
		    }

			    // option slice segment leg id
			    $str .= "<id>{$row['opt_slice_seg_leg_id']}</id>";
			    // option slice segment leg aircraft
			    $str .= "<aircraft>{$row['opt_slice_seg_leg_aircraft']}</aircraft>";
			    // option slice segment leg arrival time
			    $str .= "<arrival>{$row['opt_slice_seg_leg_arrival_time']}</arrival>";
			    // option slice segment leg departure time
			    $str .= "<departure>{$row['opt_slice_seg_leg_departure_time']}</departure>";
			    // option slice segment leg origin
			    $str .= "<origin>{$row['opt_slice_seg_leg_origin']}</origin>";
			    // option slice segment leg destination
			    $str .= "<destination>{$row['opt_slice_seg_leg_destination']}</destination>";
			    // option slice segment leg duration
			    $str .= "<duration>{$row['opt_slice_seg_leg_duration']}</duration>";
			    // option slice segment leg mileage
			    $str .= "<mileage>{$row['opt_slice_seg_leg_mileage']}</mileage>";
			    // option slice segment leg meal
			    $str .= "<meal>{$row['opt_slice_seg_leg_meal']}</meal>";

		    if ($optSliceSegLegIdFlag)
			$str .= "</leg>";

		if ($optSliceSegIdFlag)
		    $str .= "</segment>";

	    if ($optSliceNumFlag) 
		$str .= "</slice>";

	if ($optIdFlag)
	    $str .= "</option>";

	$prevRow = $row;
    } // for

    $str .= "</xml>";
    $xml = new SimpleXMLElement($str);
    echo $xml->asXML();
    //echo $str;
?>
