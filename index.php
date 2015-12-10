<?php
session_start();
include_once 'dbconnect.php';

if(!isset($_SESSION['user']))
{
	header("Location: index.php");
}
$res=mysql_query("SELECT users.*, employees.* FROM users  NATURAL JOIN employees WHERE user_id=".$_SESSION['user']);
$userRow=mysql_fetch_array($res);

?>
<!DOCTYPE html>
<html lang="en">
 <head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../favicon.ico">
	
	<title>Welcome - <?php echo $userRow['username']; ?></title>
	<!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Custom styles for this template -->
	<link href="jumbotron-narrow.css" rel="stylesheet">
	
	<!-- debug and js -->
	<script src="../assets/js/ie-emulation-modes-warning.js"></script>
	
	<!-- CSS FOR MY CALENDAR -->
	<link href="../../dist/css/bootstrap-combined.min.css" rel="stylesheet">	
	<link href="../../dist/css/fullcalendar.css" rel="stylesheet" />
	<link href="../../dist/css/fullcalendar.print.css" rel="stylesheet" />
	<!-- <link href="calenda.css" rel="stylesheet">  -->
  
	<!-- SCRIPTS FOR MY CALENDAR -->
	<script class="cssdesk" src="../assets/js/jquery.min.js" type="text/javascript"></script>
	<script class="cssdesk" src="../assets/js/jquery-ui.min.js" type="text/javascript"></script>
	<script class="cssdesk" src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script class="cssdesk" src="../assets/js/fullcalendar.min.js" type="text/javascript"></script>
	
	
 </head>

 <body>
	<div class="container">
		<div class="header clearfix">
			<nav>
			  <ul class="nav nav-pills pull-right">
				<li role="presentation"><a href="logout.php?logout">Log Out</a></li>
			  </ul>
			</nav>
			<h2 class="text-muted">Hi, <?php echo $userRow['last_name'];?>&nbsp; | Employee No: <?php echo $userRow['emp_no'];?></h2>
		</div>

		<div class="jumbotron">
			<h2>Please time stamp</h2>
			<div class="container">
				<div id='calendar'></div>
			</div>			
		</div>
			<br>
		<table align='center'>
			<td>
				<form>
					<input type="button" class="btn btn-lg btn-success" value="Clock In" id="my-button">&nbsp
				</form>
			</td>
			<td>
				<form>						
					<input type="button" class="btn btn-lg btn-success" value="Clock Out" id="clkout">&nbsp
				</form> 
			</td>
			<td>
				<form>						
					<input type="button" class="btn btn-lg btn-success" value="Hours" id="hours">
				</form>
			</td>
		</table>
		
	
		<!-- Javascript -->
		<script>
			$(function() {
			  var date = new Date();
			  var d = date.getDate();
			  var m = date.getMonth();
			  var y = date.getFullYear();

			  $('#calendar').fullCalendar({
				header: {
				  left: 'prev,next today',
				  center: 'title',
				  right: 'month,agendaWeek,agendaDay'
				},
				editable: true
			  });
			});
			//clock in button
			$('#my-button').click(function() {
				var moment = $('#calendar').fullCalendar('getDate');
				var dateObj = new Date(moment) /* Or empty, for today */
				dateIntNTZ = dateObj.getTime() - dateObj.getTimezoneOffset() * 60 * 1000;
				dateObjNTZ = new Date(dateIntNTZ);
				var nd = dateObjNTZ.toISOString().slice(0, 19);
								
				$.ajax({ 
					url: 'clockin.php',
					data: { inDate: nd.replace('T', ' ')},
					type: 'post',
					success: function(data) {
							alert(data);  
					},
					error: function(data) {
						alert("Error."); 
					}
				});
			})
			//clock out button
			$('#clkout').click(function() {
				var moment = $('#calendar').fullCalendar('getDate');
				var dateObj = new Date(moment) /* Or empty, for today */
				dateIntNTZ = dateObj.getTime() - dateObj.getTimezoneOffset() * 60 * 1000;
				dateObjNTZ = new Date(dateIntNTZ);
				var nd = dateObjNTZ.toISOString().slice(0, 19);
								
				$.ajax({ 
					url: 'clock_out.php',
					data: { outDate: nd.replace('T', ' ')},
					type: 'post',
					success: function(data) {
							alert(data);  
					},
					error: function(data) {
						alert("Error."); 
					}
				});
			})
			
			//hours button
			$('#hours').click(function() {
				var moment = $('#calendar').fullCalendar('getDate');
				var dateObj = new Date(moment) /* Or empty, for today */
				dateIntNTZ = dateObj.getTime() - dateObj.getTimezoneOffset() * 60 * 1000;
				dateObjNTZ = new Date(dateIntNTZ);
				var nd = dateObjNTZ.toISOString().slice(0, 19);
								
				$.ajax({ 
					url: 'hours.php',
					data: { hoursDone: nd.replace('T', ' ')},
					type: 'post',
					success: function(data) {
							alert(data);  
					},
					error: function(data) {
						alert("Error."); 
					}
				});
			})
		</script>
	</div>
	<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
 </body>
</html>
