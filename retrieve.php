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
    } else echo "_GET not set";
    
    $query = <<<_QUERY
	SELECT *
	FROM `{$id}`
	ORDER BY 
		 opt_saletotal ASC,
		 opt_id ASC,
		 opt_slice_num ASC,
		 opt_slice_seg_leg_departure_time;
_QUERY;
    $result = $connection->query($query);
    if (!$result) die($connection->connect_error); 

    $str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    $str .= "<xml>\n";

    $i = 0;
    $result->data_seek($i);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $curr = $row;
    $nrows = $result->num_rows;
    while ($i < $nrows)
    {
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	// get opt id
	$str .= "\t<OPTION>\n";
	$str .= "\t\t<OPTION_ID>{$row['opt_id']}</OPTION_ID>\n";
	$str .= "\t\t<SALETOTAL>{$row['opt_saletotal']}</SALETOTAL>\n";

	while ($row['opt_id'] == $curr['opt_id'] && $i < $nrows)
	{
	    $str .= "\t\t<SLICE>\n";
	    $str .=	"\t\t\t<SLICE_NUMBER>{$row['opt_slice_num']}</SLICE_NUMBER>\n";

	    while (/*$row['opt_id'] == $curr['opt_id'] &&*/
		   $row['opt_slice_num'] == $curr['opt_slice_num'] && $i < $nrows)
	    {

		++$i;
		//$curr = $row;
		$result->data_seek($i);
		$row = $result->fetch_array(MYSQLI_ASSOC);
	    } // while each each slice parameter

	    $str .= "\t\t</SLICE>\n";

	    ++$i;
	    $curr = $row;
	} // while each option parameter

	$str .= "\t</OPTION>\n";
	++$i;
	$curr = $row;
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQLI_ASSOC);
    } // while each option row

    $str .= "</xml>";
    $xml = new SimpleXMLElement($str);
    echo $xml->asXML();
?>
