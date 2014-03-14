<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);


$tdesc=$_POST['tdesc'];
$trainer=$_POST['trainer'];
$sdatedb=$_POST['sdatedb'];
$edatedb=$_POST['edatedb'];
$trainees=$_POST['trainee'];



$query="INSERT INTO Training_Plan (Training_Desc,
								Trainer_Name,
								Training_Start_Date,
								Training_End_Date) ";
$query.="VALUES('$tdesc',
				'$trainer',
				'$sdatedb',
				'$edatedb');";

print($query);

$res=mysql_query($query) or die(mysql_error());
$id=mysql_insert_id();
$result=mysql_affected_rows();
$trainees=array_values($trainees);

for($i=0;$i<count($trainees);$i++)
{

	$q="INSERT INTO Trainee_Feedback (Training_Plan_ID,Trainee_ID) VALUES('$id','$trainees[$i]');";
	$resq=mysql_query($q) or die(mysql_error());

}


if($result!=0)
{
print("New Training Plan Added With Plan ID of $id");	
	
}else
	{
		print("Error Adding");
	}


?>