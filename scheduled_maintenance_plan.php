<?
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT *,Start_Date_Time,End_Date_Time,Machine_Name,Operator_Name,Maintenance_Description,SPM_ID From Maintenance as maint
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

if($row['SPM_ID']!='')
{

	$q5="SELECT SPM_Title FROM Scheduled_PM WHERE SPM_ID=$row[SPM_ID];";
	$res5=mysql_query($q5, $cxn) or die(mysql_error($cxn));	
	$f5=mysql_fetch_assoc($res5);
	$mainttype=$f5['SPM_Title'];
}else{

$mainttype = $row['Maintenance_Description'];		
}
 $sdt = $row['Start_Date_Time'];
 $edt=$row['End_Date_Time'];
 $id=$row['Activity_Log_ID'];
 // Stores each database record to an array
 $buildjson = array('title' => "$row[Machine_Name] - $mainttype",'id' => "$id", 'start' => "$sdt",'end' => "$edt", 'allday' => false);

 // Adds each array into the container array
 array_push($jsonArray, $buildjson);
}


$qspm="SELECT * FROM Scheduled_PM;";

$res=mysql_query($qspm, $cxn) or die(mysql_error($cxn));
while($spm=mysql_fetch_assoc($res))
{

		$qs="SELECT *,DATE_FORMAT(End_Date_Time,'%Y/%m/%d')as edt,Machine_Name FROM Maintenance as maint 
			INNER JOIN ActivityLog as actl ON actl.Activity_Log_ID=maint.Activity_Log_ID 
			INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID 
			WHERE SPM_ID='$spm[SPM_ID]' ORDER BY End_Date_Time LIMIT 1;";

			$resspm=mysql_query($qs, $cxn) or die(mysql_error($cxn));
			$r=mysql_affected_rows();
			if($r!=0)
			{
				$rfe=mysql_fetch_assoc($resspm);
				$nextspmdate=date('Y-m-d', strtotime($rfe[edt]. ' + '.$spm[SPM_Interval].' days'));
//				$lastpmdate = strtotime($rfe['edt']);
//				$nextspmdate = date('Y-m-d', mktime(0,0,0,date('m',$lastpmdate),date('d',lastpmdate)+$spm['SPM_Interval'],date('Y',$lastpmdate)));
//				print("machine=$rfe[Machine_Name] lastpm=$rfe[edt] and interval=$spm[SPM_Interval] and next pm=$nextspmdate");
 				$buildjson = array('title' => "$rfe[Machine_Name] - $spm[SPM_Title]", 'start' => "$nextspmdate",'backgroundColor' => "orange", 'allday' => false);
				array_push($jsonArray, $buildjson);
	 		}
 	
}



// Output the json formatted data so that the jQuery call can read it
echo json_encode($jsonArray);
?>