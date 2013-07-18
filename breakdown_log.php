<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);
$uploadDir = '/home/www/logimages/';


$activityid=$_POST['Activity_ID'];
$machineid=$_POST['Machine_ID'];
$sdatetime=$_POST['sdatedb'];
$edatetime=$_POST['edatedb'];
$operatorid=$_POST['Operator_ID'];
$maintid=$_POST['Maintenance_Type_ID'];
if(isSet($_POST['bddetail'])){$bddetails=$_POST['bddetail'];}else{$bddetails='';}
if(isSet($_POST['wodetail'])){$wodetails=$_POST['wodetail'];}else{$wodetails='';}
if(isSet($_POST['mkengr'])){$mkengr=$_POST['mkengr'];}else{$mkengr="";}
if(isSet($_POST['spares'])){$spares=$_POST['spares'];}else{$spares="";}
if(isSet($_POST['remark'])){$remark=$_POST['remark'];}else{$remarks="";}
if(isSet($_POST['pm'])){$pm=$_POST['pm'];}else{$pm="";}
if(isSet($_POST['SPM_ID'])){$spmid=$_POST['SPM_ID'];}else{$spmid="";}
if($pm!='')
{
$pm=implode(',', $pm);
}

if((isSet($_FILES['oimg']['name']))&&($_FILES['oimg']['name']!='')){

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

$pquery="INSERT INTO Maintenance (Activity_Log_ID,
								Service_Engr_Name,
								Problem_Desc,
								Maintenance_Desc,
								Spares_Used,
								Maintenance_Type_ID,
								Sch_Prev_Maint_IDs,
								SPM_ID) ";
$pquery.="VALUES('$lastid',
				'$mkengr',
				'$bddetails',
				'$wodetails',
				'$spares',
				'$maintid',
				'$pm',
				'$spmid');";

//print("<br>$pquery");
$result=mysql_query($pquery) or die(mysql_error());
$ok=mysql_affected_rows();
if($ok!=0)
{
	print("Added one Row in to Maintenance Log and Log ID is $lastid");
}else{
	print("Error adding into Maintenance Log");
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