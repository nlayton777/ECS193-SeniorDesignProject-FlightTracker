<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<script src="jquery-2.1.3.js"></script>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="bootstrap.js"></script>
	
</head>

<body>
<?php
	$to = "LAX";
	$from = "SFO";
	$java = 'java sample/Main ' . $to . ' ' . $from;
	$output = shell_exec($java);
	if (!(strpos($output,'ERROR') !== false)) {
		echo $output;
	}
	
	$myArray = explode(', ', $output);
?>

   <div class="container">
	 <h2>To Find the Best Price, Hopper.com suggests:</h2>
	 <table class="table">
	   <tbody>
		 <tr>
		   <td>A <b>Good Price</b> would be</td>
		   <td><?php echo $myArray[2]?></td>
		 </tr>
		 <tr>
		   <td>Try <b>Flying Out</b> on a</td>
		   <td><?php echo $myArray[3]?></td>
		 </tr>
		 <tr>
		   <td>Try <b>Flying Back</b> on a</td>
		   <td><?php echo $myArray[4]?></td>
		 </tr>
		 <tr>
		   <td>Try these <b>Airlines</b></td>
		   <td><?php 
		   			for ($x = 0; $x < $myArray[5]; $x++) {
		   				echo $myArray[6+$x];
		   				if($x+1 < $myArray[5])
		   				{
		   					echo ", ";
		   				}
		   					
		   			}
		   		?>
		   </td>
		 </tr>
		 <tr>
		   <td>Also look at flights <b>Departing From</b></td>
		   <td><?php echo $myArray[5+$myArray[5]+1]?></td>
		 </tr>
		 <tr>
		   <td>Also look at flights <b>Arriving Into</b></td>
		   <td><?php echo $myArray[5+$myArray[5]+2]?></td>
		 </tr>
	   </tbody>
	 </table>
	 <p><a href="<?php echo 'http://www.hopper.com/flights/from-' . $myArray[0] . '/to-' . $myArray[1] . '/guide' ?>">See for Yourself!</a></p>            

   </div>

<?php
	$originalDate = "05/03/2015";
	$newDate = date("Y-m-d", strtotime($originalDate));
	$urlString = "https://www.google.com/flights/#search;f={$newDate}-";
	$urlString = substr($urlString, 0, -1);
	echo $urlString;
?>
</body>


</html>