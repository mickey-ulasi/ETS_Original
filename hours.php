<?php
 session_start();

    /* connect to DB */
    include_once 'dbconnect.php';

    if(isset($_POST['hoursDone3']))
    {
        $indate = mysql_real_escape_string($_POST['hoursDone']);

        $clock_id = NULL;
        $clock_in = $indate;
        $timestamp_in = $indate;
        $user_id = $_SESSION['user'];
		
		//insert query stmt
        $res2 = mysql_query("SELECT user_id, emp_login.clock_in, emp_logout.clock_out, 
            TIMESTAMPDIFF(second, clock_in, clock_out) / 3600 as TotalHours from `emp_login` NATURAL JOIN `emp_logout`
            WHERE user_id=".$_SESSION['user_id']);

        if($res2)
            echo "User in time entry successfully made.";
        else
            echo "Error inserting entry data: ".mysql_error();
    }
    else
        echo "No date received.";
?>


