<?
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT *,Start_Date_Time,End_Date_Time,Machine_Name,Operator_Name,Maintenance_Description From Maintenance as maint
		INNER JOIN ActivityLog as actl ON actl.Activity_Log_ID=maint.Activity_Log_ID
		INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
		INNER JOIN Operator as ope ON ope.Operator_ID=actl.Operator_ID
		INNER JOIN Maintenance_Type as mty ON mty.Maintenance_Type_ID=maint.Maintenance_Type_ID
		WHERE actl.Activity_ID=5;";



$res=mysql_query($query, $cxn) or die(mysql_error($cxn));

// Initializes a container array for all of the calendar events
$jsonArray = array();

while($row = mysql_fetch_array($res))
{

$mainttype = $row['Maintenance_Description'];		

 $sdt = $row['Start_Date_Time'];
 $edt=$row['End_Date_Time'];
$id=$row['Activity_Log_ID'];
 // Stores each database record to an array
 $buildjson = array('title' => "$mainttype",'id' => "$id", 'start' => "$sdt",'end' => "$edt", 'allday' => false);

 // Adds each array into the container array
 array_push($jsonArray, $buildjson);
}






// Output the json formatted data so that the jQuery call can read it
echo json_encode($jsonArray);
?>