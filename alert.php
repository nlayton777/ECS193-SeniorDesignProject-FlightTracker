<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Buttons and alerts</title>
<link href="bootstrap.css" rel="stylesheet">
<script src="jquery-2.1.3.js"></script>
<script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/js/bootstrap-alert.js"></script>
<script src="bootstrap.js"></script>
<script type="text/javascript">
function validate(){
  $('#message').html('<div class="alert alert-danger fade in"><button type="button" class="close close-alert" data-dismiss="alert" aria-hidden="true">×</button>This is a error message</div>');
};
</script>
</head>

<body>
<div class="container">
<h2>Buttons and Alerts</h2>
<p class="lead">This example shows off how to use buttons and jQuery together. 
Click the buttons to see the alert message. </p>
       <p>
       <form method="post">
       <button type="button" class="btn" id="submit" onclick="validate()">Submit</button>
     </form>
      </p>
      <div id="message"></div>
</div>
</body>
</html>   