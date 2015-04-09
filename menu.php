<!DOCTYPE html>
<html>
<!--this is for checkbox list-->
	<script type="text/javascript" src="bootstrap-multiselect.js"></script>
	<link rel="stylesheet" href="bootstrap-multiselect.css" type="text/css"/>

<!--AIRLINE FIELD-->
			<label for="airline" class="sr-only">Preferred Airline</label>
			<select class="form-control" id="airline" name="airline[]"
				    form="search_form">
			<option value="none" selected="selected">--Select an Airline--</option>
			<option value="none">No Preference</option>
			<?php
			    if (file_exists("airlines.txt")){
				$codes = fopen("airlines.txt",'r');	
				while($buffer = fgets($codes, 4096)){	
				    $sub = substr($buffer, -4, 2);
				    echo "<option value=\"" . $sub .
					"\">" . $buffer . "</option>";
				}
			    } 
			?>
			</select>
			
</html>