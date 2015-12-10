<?php
 session_start();

    /* connect to DB */
    include_once 'dbconnect.php';

    if(isset($_POST['outDate']))
    {
        $indate = mysql_real_escape_string($_POST['outDate']);

        $clock_id = NULL;
        $clock_out = $indate;
        //$timestamp_out = $indate;
        $user_id = $_SESSION['user'];
		
		//insert query stmt
        $res2 = mysql_query("INSERT INTO `employees`.`emp_logout` (`clock_id`, `clock_out`, `user_id`) 
            VALUES ('$clock_id', '$clock_out', '$user_id')");
        $res=mysql_query("SELECT users.*, employees.* FROM users  NATURAL JOIN employees WHERE user_id=".$_SESSION['user']);
        $userRow=mysql_fetch_array($res);

        if($res2) 
        {
            echo "Thank You "; echo $userRow['last_name']; echo".\n";
            echo "Your clock out time has been registered successfully.";
        }
            
        else
            echo "Error inserting entry data: ".mysql_error();
    }
    else
        echo "No date received.";
?>


