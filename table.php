<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="bootstrap.css"/>
		<script src="jquery-2.1.3.js"/></script>
		<script src="bootstrap.js"></script>
		<link rel="stylesheet" href="styles.css"/>
	</head>

	<?php
		$to = 'PHF';
		$from = 'MCO';
		$java = 'java sample/Main ' . $to . ' ' . $from;
				 	$output = shell_exec($java);
					$myarray = array();
				 	if (!(strpos($output,'ERROR') !== false)){
				 		$myArray = explode(', ', $output);
				 	}

	echo <<<_TABLE1
		<h2>To Find the Best Price, Hopper.com suggests:</h2>
			<table class="table">
				<tbody>
_TABLE1;
		
		if($myArray[2] != ''){
			echo <<< _Row1
			<tr>
				<td>A <b>Good Price</b> would be</td>
				<td>{$myArray[2]} (per passenger)</td>
			</tr>
_Row1;
		}
		if($myArray[3] != ''){
			echo <<< _Row2
					  			<tr>
									<td>Try <b>Flying Out</b> on a</td>
									<td>{$myArray[3]}</td>
					  			</tr>
_Row2;
		}
?>
</html>