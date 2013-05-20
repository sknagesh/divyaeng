<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);

$uploadDir = '/home/www/logimages/';

$actlid=$_POST['lid'];
$drawingid=$_POST['Drawing_ID'];
$machineid=$_POST['Machine_ID'];
$operationid=$_POST['Operation_ID'];
$sdatetime=$_POST['sdatedb'];
$edatetime=$_POST['edatedb'];
$progno=$_POST['pno'];
$operatorid=$_POST['Operator_ID'];
$qty=$_POST['qty'];
$bid=$_POST['Batch_ID'];
$activityid=$_POST['Activity_ID'];

if(isSet($_POST['remark'])){$remarks=$_POST['remark'];}else{$remarks="";}

if(isSet($_POST['simg'])){$simg=$_POST['simg'];}else{$simg='';}


if(isSet($_FILES['oimg']['name'])){
	
$oimgfiles=count($_FILES['oimg']['name']);	
	
}else{
	$oimgfiles='';
}



$query="UPDATE ActivityLog SET Activity_ID='$activityid',
								Machine_ID='$machineid',
								Start_Date_Time='$sdatetime',
								End_Date_Time='$edatetime',
								Operator_ID='$operatorid',
								Remarks='$remarks' WHERE Activity_Log_ID=$actlid; ";

print("<br>$query");

$res=mysql_query($query) or die(mysql_error());


$pquery="UPDATE Production SET Operation_ID=$operationid,
								Program_NO='$progno',
								Batch_ID='$bid',
								Quantity=$qty WHERE Activity_Log_ID=$actlid; ";

//print("<br>$pquery");

$result=mysql_query($pquery) or die(mysql_error());

print("Updated Log ID $actlid");
 

 if($simg!='')
{
$simages=count($simg);

$simg=array_values($simg); //re-order array

	for ($j=0; $j<$simages; $j++)
	{
//select file name and delete them from HDD and path from database
		$qf="SELECT Image_Path FROM ActivityLog_Image WHERE AL_Image_ID=$simg[$j];";
		$rf=mysql_query($qf) or die(mysql_error());
		$rfr=mysql_fetch_assoc($rf);
		$filepath=$uploadDir.$rfr['Image_Path'];
		print($filepath);
		unlink($filepath);	
		$q="DELETE FROM ActivityLog_Image WHERE AL_Image_ID=$simg[$j];";
		$rf=mysql_query($q) or die(mysql_error());
		}
}


//add new images selected for this log 
if($oimgfiles!=0)
{
	foreach ($_FILES['oimg']['name'] as $key => $name) {
		


	$drgfileName = $actlid."-".$_FILES['oimg']['name'][$key];
	$drgtmpName = $_FILES['oimg']['tmp_name'][$key];
	$drgfileSize = $_FILES['oimg']['size'][$key];
	$drgfileType = $_FILES['oimg']['type'][$key];
	$drgfilePath = $uploadDir . $drgfileName;
	$result = move_uploaded_file($drgtmpName, $drgfilePath);
	if (!$result) {
						echo "<br>Error uploading Activity Image $drgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$drgfileNames[$key] = addslashes($drgfileName);
						$drgfilePath = addslashes($drgfilePath);
						}
						}

}else{$drgfileNames='';}





if($oimgfiles!='')
{

		for ($i=0; $i < $oimgfiles; $i++) { 
			$quef="INSERT INTO ActivityLog_Image (Activity_Log_ID,Image_Path) VALUES( '$actlid','$drgfileNames[$i]');";
			print($quef);
			$resf=mysql_query($quef) or die(mysql_error());
	}


}
 
  
?>