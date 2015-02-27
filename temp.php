<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>


    <!-- FOR DROP DOWN IN TABLE 
	<link rel="stylesheet" href="../../jqwidgets/styles/jqx.base.css" type="text/css" />
	<script type="text/javascript" src="../../scripts/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="../../jqwidgets/jqxcore.js"></script>
	<script type="text/javascript" src="../../jqwidgets/jqxdata.js"></script>
	<script type="text/javascript" src="../../jqwidgets/jqxbuttons.js"></script>
	<script type="text/javascript" src="../../jqwidgets/jqxscrollbar.js"></script>
	<script type="text/javascript" src="../../jqwidgets/jqxmenu.js"></script>
	<script type="text/javascript" src="../../jqwidgets/jqxgrid.js"></script>
	<script type="text/javascript" src="../../jqwidgets/jqxgrid.selection.js"></script>
	<script type="text/javascript" src="../../jqwidgets/jqxdropdownbutton.js"></script> 
-->
    </head>

    <body>
	<nav class="navbar navbar-inverse ">
	    <div class="container-fluid">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mynavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="index.php">Flight Tracker</a>
		</div>
		<div class="collapse navbar-collapse" id="mynavbar">
		    <ul class="nav navbar-nav">
			<li class="active">
			    <a href="index.php">Search</a>
			</li>
			<li>
			    <a href="about.php">About</a>
			</li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li>
			    <a href="contact.php">Contact</a>
			</li>
		    </ul>
		</div>
	    </div>
	</nav>

<div class="container-fluid" id="searchheader">
    <div class="row">
	<div class="col-xs-4 col-md-2"></div>
	<div class="col-xs-10 col-md-8">
	    <h1>Search Results</h1>
	    <h3>Our search bot found these travel options just for you!</h3>	

	    <table class="table table-hover background-color:#CCF5F6">
		<tr>
		    <th>Price</th>
		    <th colspan='6'>Flight Info</th>
		</tr>

		<tr>
		    <td>$500.00</td>
		    <td> 10:00AM <strong>SFO</strong> &rarr; 1:00PM <strong>JFK</strong>    6h0m    (1 stop PHX)
			    <div id="divTest">
				<table cellspacing="10" cellpadding="10" id="expandedtable" class="table">
				    <tr>
					    <th>Stopover Number </th>
					    <th>Point A</th>
					    <th>Point B</th>
				    </tr>

				    <tr>
					<td>1</td>
					<td>11:00AM PHX</td>
					<td>2:00PM JFK</td>
    				    </tr>
			    </table>
			    </div>
		    </td>
		    <td>
			<input type="button" id="btnExpCol" onclick="Expand();" value="Expand  "/>
		    </td>
		</tr>

		<tr>
		    <td>$550.00</td>
		    <td> 10:30AM <strong>SFO</strong> &rarr; 1:45PM <strong>JFK</strong>    6h15m    (1 stop SEA)</td>
		</tr>	
	</div>


	    </table>
	<div class="col-xs-4 col-md-2"></div>
    </div>
</div>

<!-- Button animation code -->
<script>
    window.onload=function(){
	$('#divTest').hide();
    
    };
    $(document).ready(function () {
     $('#btnExpCol').click(function () {
        if ($(this).val() == 'Collapse') {
		                         
					 
	$('#divTest').stop().slideUp('3000');
	    $(this).val('Expand  ');
	} else {
		    
	$('#divTest').stop().slideDown('3000');
	    $(this).val('Collapse');		     
	}										                 
    });
	});
</script>
																		            


</body>

</html>
