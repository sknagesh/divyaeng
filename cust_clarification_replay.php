<?php
include('dewdb.inc');

$uploadDir = '/home/www/enquiry/';
//print_r($_POST);

$today = date("Y-m-d H-i");
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$ccid = $_POST['Clarification_ID'];
$rdatedb=$_POST['rdatedb'];
$rdate=$_POST['rdate'];
if(isSet($_POST['remarks'])){$remarks=$_POST['remarks'];}else{$remarks='';}


if((isSet($_FILES['drg']['name']))&&$_FILES['drg']['name']!='')
{
	$drgfileName = $ccid."-".$today."-".$_FILES['drg']['name'];
	$drgtmpName = $_FILES['drg']['tmp_name'];
	$drgfileSize = $_FILES['drg']['size'];
	$drgfileType = $_FILES['drg']['type'];
	$drgfilePath = $uploadDir . $drgfileName;
	$result = move_uploaded_file($drgtmpName, $drgfilePath);
	chmod($drgfilePath, 777);
	if (!$result) {
						echo "<br>Error uploading Clarification $drgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$drgfileName = addslashes($drgfileName);
						$drgfilePath = addslashes($drgfilePath);
						}

}else{$drgfileName='';}





$q1="UPDATE Cust_Clarification SET Date_OF_Clarification='$rdatedb',
									Remarks='$remarks',
									Replay_Path='$drgfileName' WHERE Clarification_ID='$ccid';";
$r1 = mysql_query($q1, $cxn) or die(mysql_error($cxn));


?>