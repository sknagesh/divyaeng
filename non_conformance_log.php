<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);
$uploadDir = '/home/www/logimages/';

$custid=$_POST['Customer_ID'];
$drawingid=$_POST['Drawing_ID'];
$activityid=$_POST['Activity_ID'];
$sdatetime=$_POST['edatedb'];
$operatorid=$_POST['Operator_ID'];
$bno=$_POST['Batch_ID'];
$ncid=$_POST['NC_ID'];
$statusid=$_POST['Status_ID'];
$ncdesc=$_POST['ncdesc'];
$rootca=$_POST['rootca'];
$corract=$_POST['corract'];


if(isSet($_POST['remark'])){$remark=$_POST['remark'];}else{$remarks="";}

if(isSet($_POST['ncp'])){$ncp=$_POST['ncp'];}else{$ncp="";}

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
				'1',
				'$sdatetime',
				'0-0-0 0:0:0',
				'$operatorid',
				'$remark');";

//print("<br>$query");

$res=mysql_query($query) or die(mysql_error());
$lastid=mysql_insert_id();

$pquery="INSERT INTO NonConformance (Activity_Log_ID,
								Drawing_ID,
								NC_ID,
								Problem_Description,
								Root_Cause,
								Corr_Action,
								NC_Status_ID,
								NC_Percentage,
								Batch_ID) ";
$pquery.="VALUES('$lastid',
				'$drawingid',
				'$ncid',
				'$ncdesc',
				'$rootca',
				'$corract',
				'$statusid',
				'$ncp',
				'$bno');";

//print("<br>$pquery");
$result=mysql_query($pquery) or die(mysql_error());
$ok=mysql_affected_rows();
if($ok!=0)
{
	print("Added one Row in to Non Conformance Log with Batch ID $bno and Log ID is $lastid");
}else{
	print("Error adding into Non Conformance Log");
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