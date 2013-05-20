<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$uploadDir = '/home/www/logimages/';


$drawingid=$_POST['Drawing_ID'];
$activityid=$_POST['Activity_ID'];
$machineid=$_POST['Machine_ID'];
$operationid=$_POST['Operation_ID'];
$sdatetime=$_POST['sdatedb'];
$edatetime=$_POST['edatedb'];
$operatorid=$_POST['Operator_ID'];
$qty=$_POST['qty'];
$bno=$_POST['Batch_ID'];
if(isSet($_POST['remark'])){$remark=$_POST['remark'];}else{$remark="";}


if(isSet($_FILES['oimg']['name'])){
	
$oimgfiles=count($_FILES['oimg']['name']);	
	
}else{
	$oimgfiles='';
}



$query="INSERT INTO ActivityLog (Activity_ID,
								Machine_ID,
								Start_Date_Time,
								End_Date_Time,
								Operator_ID,
								Remarks) ";
$query.="VALUES('$activityid',
				'$machineid',
				'$sdatetime',
				'$edatetime',
				'$operatorid',
				'$remark');";

//print("<br>$query");

$res=mysql_query($query) or die(mysql_error());
$lastid=mysql_insert_id();

$pquery="INSERT INTO Production (Activity_Log_ID,
								Operation_ID,
								Program_NO,
								Quantity,
								Batch_ID) ";
$pquery.="VALUES('$lastid',
				'$operationid',
				'',
				'$qty',
				'$bno');";

//print("<br>$pquery");
$result=mysql_query($pquery);
if(!$result)
{
$q="DELETE FROM ActivityLog WHERE Activity_Log_ID='$lastid';";
$rd=mysql_query($q) or die(mysql_error());
	
}else{
$ok=mysql_affected_rows();
}
if($ok!=0)
{
	print("Recorded one Rejection Batch ID $bno and Log ID is $lastid");
}else{
	print("Error adding into Rejection Log");
}


if($oimgfiles!=0)
{
	foreach ($_FILES['oimg']['name'] as $key => $name) {
		


	$drgfileName = $lastid."-".$_FILES['oimg']['name'][$key];
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
			$quef="INSERT INTO ActivityLog_Image (Activity_Log_ID,Image_Path) VALUES( $lastid,'$drgfileNames[$i]');";
//			print($quef);
			$resf=mysql_query($quef) or die(mysql_error());
	}


}
?>