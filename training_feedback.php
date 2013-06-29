<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
//print_r($_FILES);
$uploadDir = '/home/www/training/';

$tid=$_POST['Training_Plan_ID'];
$traineeid=$_POST['Trainee_ID'];
$feedback=$_POST['feedback'];
$ftrain=$_POST['ftrain'];

if((isSet($_FILES['teval']['name']))&&$_FILES['teval']['name']!='')
{
	$drgfileName = $tid."-".$traineeid."-".$_FILES['teval']['name'];
	$drgtmpName = $_FILES['teval']['tmp_name'];
	$drgfileSize = $_FILES['teval']['size'];
	$drgfileType = $_FILES['teval']['type'];
	$drgfilePath = $uploadDir . $drgfileName;
	$result = move_uploaded_file($drgtmpName, $drgfilePath);
	chmod($drgfilePath, 777);
	if (!$result) {
						echo "<br>Error uploading Evaluation File $drgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$drgfileName = addslashes($drgfileName);
						$drgfilePath = addslashes($drgfilePath);
						}

}else{$drgfileName='';}



$query="UPDATE Trainee_Feedback SET Trainee_Feedback='$feedback',
								Further_Training_Required='$ftrain',
								Training_Evaluation_Path='$drgfileName'
								WHERE Training_Plan_ID='$tid' and Trainee_ID='$traineeid'; ";

print($query);

$res=mysql_query($query) or die(mysql_error());
$result=mysql_affected_rows();

if($result!=0)
{
print("Training Feedback/Evaluation Updated");	
	
}else
	{
		print("Error Adding");
	}


?>