<?php
session_start();
include_once 'dbconnect.php';

if(isset($_SESSION['user'])!="")
{
	header("Location: home.php");
}

if(isset($_POST['btn-login']))
{
	//prevents SQL injecions
	$pNumber = mysql_real_escape_string($_POST['pNumber']);
	$upass = mysql_real_escape_string($_POST['pass']);	
	
	
	//used for failed attempts 
	$userIP = $_SERVER['REMOTE_ADDR'];
	$attempt_id = NULL;
	$aptSql = mysql_query("SELECT COUNT(pNumber) AS failed_log FROM attempts WHERE pNumber='$pNumber'");
	$row_count = mysql_fetch_assoc($aptSql);
	$failed_attempt = $row_count['failed_log'];		
	
	$locked_time = mysql_query("SELECT LAST_INSERT_ID(), DATE_ADD(lastlocked, INTERVAL 2 MINUTE) AS cheknow FROM `attempts` ORDER BY id DESC LIMIT 1");
	$show_row_res = mysql_fetch_array($locked_time);
	$convert_time= strtotime($show_row_res['cheknow']);
	$current_time = time();
	
	
	?>		
			
			<script>alert('The time now is : <?php echo $current_time; ?>') </script> 
			<script>alert('The converted time : <?php echo $convert_time['cheknow'];?>') </script> 
	<?php
	
	
	
	if($failed_attempt >= 3 and $convert_time > $current_time)
	{
		
		?>			
			<script>alert('Sorry, you have exceeded numbers of attempts allowed. Please see your department manager');</script> 
		<?php
			
		
	}
	else
	{
		$res=mysql_query("SELECT users.*, employees.* FROM users NATURAL JOIN employees WHERE users.pNumber='$pNumber'");
		$row=mysql_fetch_array($res);
		
			if($row['password']==md5($upass))
		{
			$_SESSION['user'] = $row['user_id'];
			header("Location: home.php");
		}
		else
		{
			//Insert login attempts to table			
			$insertSql = mysql_query("INSERT INTO `employees`.`attempts` (`id`, `ip`, `pNumber`) VALUES ('$attempt_id', '$userIP', '$pNumber')");
			
			//result 
			if($insertSql != false)
			{				
				?>
					<script>
						alert('You entered an invalid username or password, your attempt has been stored.');
					</script>
				<?php
				
			}
			
			else
			{
				?>
					<script>
						alert('Error Inserting your details. Please, see your department manager');
					</script>
				<?php
			}
		}	
	}
}


?>
<!DOCTYPE html>
<html lang="en">
 <head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="Lee & Micheal" content="">
    <link rel="icon" href="../favicon.ico">
	
	<title>Employee Time Stamp System</title>
	
	<!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
	
	<!-- debug and js -->
	<script src="../assets/js/ie-emulation-modes-warning.js"></script>
 </head>
 
 <body>
	
	<div class="container">
		
		<tr>
		<td><center><h1>EMPLOYEE TIMESTAMP SYSTEM</h1></center><br></td>
		</tr>
		 <form method="post" class="form-signin" ><br>
			<h2 class="form-signin-heading">LOGIN HERE</h2>
			<label for="inputEmail" class="sr-only">Personal ID</label>
			<input type="text" name="pNumber" id="inputEmail" class="form-control" placeholder="Personal ID" required autofocus>
			<label for="inputPassword" class="sr-only">Password</label>
			<input type="password" name="pass" id="inputPassword" class="form-control" placeholder="Password" required>			
			<button class="btn btn-lg btn-primary btn-block" type="submit" name="btn-login">Login in</button>
		 </form>
		
		
	</div> <!-- /container -->
	
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
 </body>
</html>
