<html>
<body>
<?php
    $fh = fopen('course_from_db_odd_sem.txt', 'w');
	
$user='root';
$pass='';
$db='timetable';
$db = new mysqli('localhost',$user,$pass,$db);
$temp=mysqli_query($db,"SELECT count(*) FROM course where sem_type='odd'");
$result = mysqli_query($db,"SELECT course_name,no_of_student,semester,department,lab,preference,preffered_room,preffered_slot,division,lab_room FROM course where sem_type='odd'");
$result1=mysqli_fetch_row($temp);
$counts=$result1[0];
	fwrite($fh, $counts); 
	fwrite($fh, "\n");
    while ($row = mysqli_fetch_array($result)) {          
        $last = end($row);          
        $num = mysqli_num_fields($result) ;    
        for($i = 0; $i < $num; $i++) {            
            fwrite($fh, $row[$i]);                      
            if ($row[$i] != $last)
               fwrite($fh, " ");
        }                                                                 
        fwrite($fh, "\n");
    }
    fclose($fh);
	// Close The Connection
	mysqli_close ($db);
	echo '<script type="text/javascript">alert("Courses selected Succesfully Now click generate");window.location=\'select_course.php\';</script>';
?>
</body>
<html>