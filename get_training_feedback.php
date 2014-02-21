<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$tid=$_GET['tid'];
$query="SELECT *,Operator_Name,Trainer_Name,Training_Start_Date,Training_End_Date,Training_Desc FROM Trainee_Feedback as tfb
		INNER JOIN Training_Plan as tp on tp.Training_Plan_ID=tfb.Training_Plan_ID
		INNER JOIN Operator as oper ON oper.Operator_ID=tfb.Trainee_ID
	    WHERE tfb.Training_Plan_ID='$tid';";

//print($query);

$res=mysql_query($query) or die(mysql_error());


$r=mysql_num_rows($res);
if($r!=0)
{
$r1=mysql_fetch_assoc($res);
	$sdt=$r1['Training_Start_Date'];
	$edt=$r1['Training_End_Date'];
	$trainer=$r1['Trainer_Name'];
	$td=$r1['Training_Desc'];


print("<p>Training Subject : $td");
print("<p>Training Start Date: $sdt");
print("<p>Training End Date: $edt");
print("<p>Trainer: $trainer");
print("<p>");
$c="q";
print("<table cellspacing=\"1\">");
print("<tr class=\"t\"><th>Trainee Name</th><th>Trainee Feedback</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	if($row['Further_Training_Required']==1){$ftr="Yes";}else{$ftr="No";}
	print("<tr class=\"$c\"><td>$row[Operator_Name]</td><td>$row[Trainee_Feedback]</tr>");
	if($c=="q"){$c="s";}else{$c="q";}
}
print("</table>");
}
else {
	print("No Feedback Found for This Training Plan");
}
?>