<script> 

    if (document.layers||document.getElementById||document.all)
    {


    var currentSecs = new Date().getTime()/1000; //get time right now in seconds 
	    
    var searchTime = document.getElementById("numHours").value;
    var searchSecs = searchTime * 60 * 60; //search window time in seconds 

    var currentSearchDate = currentSecs + searchSecs;

    DateTime date = new DateTime(long.Parse(currentSearchDate));
	date.ToString("MM/dd/yyyy");

		var str=document.searchwindow.email.value
    	var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
    	if (filter.test(str))
			var testresults=true
    	else{
//			alert("Please input a valid email address!")
			alert(date)
			return false;
			}
	}
    	
	//validation search time is after depart date
    var currentSecs = new Date().getTime()/1000; //get time right now in seconds 
	    
    var searchTime = document.getElementById("numHours").value;
    var searchSecs = searchTime * 60 * 60; //search window time in seconds 

    var currentSearchDate = currentSecs + searchSecs;

    DateTime date = new DateTime(long.Parse(currentSearchDate));
	date.ToString("MM/dd/yyyy");



    
   //get Departure date and convert to seconds 
	<?php 
		require_once('./flight_tracker.php');

		$post = $_POST;
		echo "var departureDate = \"{$post['depart_date']}\";";
	?>   


	var departSecs = getDateFromFormat(departureDate, "MM/DD/YYYY");

	departSecs = departSecs/1000;

    if((currentSecs + searchSecs) > departSecs)
    {
		alert("Please choose a search time that will complete before your departure date.")
	
    }

    


}

</script>