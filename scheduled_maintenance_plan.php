<?
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

if(isSet($_GET['mid'])){$mid=$_GET['mid'];}else{$mid='';}



$start=$_GET['start'];	///dummy not used
$starton = date('Y-m-d H:i',$start);  //dummy not used
$m = date("m",strtotime($starton)); ///dummy not used

if($mid!='')
{
	$mid= "AND mach.Machine_ID=".$mid;
}

$query="SELECT *,Start_Date_Time,End_Date_Time,Machine_Name,Operator_Name,Maintenance_Description,SPM_ID From Maintenance as maint
		INNER JOIN ActivityLog as actl ON actl.Activity_Log_ID=maint.Activity_Log_ID
		INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
		INNER JOIN Operator as ope ON ope.Operator_ID=actl.Operator_ID
		INNER JOIN Maintenance_Type as mty ON mty.Maintenance_Type_ID=maint.Maintenance_Type_ID
		WHERE actl.Activity_ID=5 $mid;";



$res=mysql_query($query, $cxn) or die(mysql_error($cxn));

// Initializes a container array for all of the calendar events
$jsonArray = array();

while($row = mysql_fetch_array($res))
{
 $sdt = $row['Start_Date_Time'];
 $edt=$row['End_Date_Time'];
 $id=$row['Activity_Log_ID'];

///clear previous values 
$spminterval='';
$spmtol='';
$spmtitle='';
$maintinterval='';
$td='';


if(($row['SPM_ID']!='')&&($row['SPM_ID']!=0))
{
	$q5="SELECT SPM_Title,SPM_Interval,SPM_Tol FROM Scheduled_PM WHERE SPM_ID=$row[SPM_ID];";
//	print("<p>$q5");
	$res5=mysql_query($q5, $cxn) or die(mysql_error($cxn));	
	$f5=mysql_fetch_assoc($res5);
	$spminterval=$f5['SPM_Interval'];
	$spmtol=$f5['SPM_Tol'];
	$mainttype=$f5['SPM_Title'];

		$q6="SELECT *,DATE_FORMAT(End_Date_Time,'%Y/%m/%d')as edt FROM Maintenance as maint 
			INNER JOIN ActivityLog as actl ON actl.Activity_Log_ID=maint.Activity_Log_ID 
			INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID 
			WHERE SPM_ID='$row[SPM_ID]' AND actl.Machine_ID=$row[Machine_ID] AND actl.End_Date_Time <'$row[End_Date_Time]' 
			ORDER BY End_Date_Time Desc LIMIT 1;";
//print("$q6<br>");
			$r6=mysql_query($q6, $cxn) or die(mysql_error($cxn));
			$r=mysql_num_rows($r6);
			if($r)
			{
				$row6=mysql_fetch_assoc($r6);
				
				$date1 = $row['End_Date_Time'];
				$date2 = $row6['edt'];

				$ts1 = strtotime($date1);
				$ts2 = strtotime($date2);

				$seconds_diff = $ts2 - $ts1;

				$maintinterval=floor($seconds_diff/3600/24)+$spminterval;

				if(($maintinterval<-$spmtol)||($maintinterval>$spmtol))
				{
					$color="red";
					$mi=abs($maintinterval)-abs($spmtol);
				}else{
						$color="green";
				}

				if($maintinterval<-$spmtol)
				{
					$td=" Late By $mi Days";
				}else
				if($maintinterval>$spmtol)
				{
					$td=" Early By $mi Days";
				}else{
					$td=" On Time";
				}

								
	 		}

if($maintinterval=='')
			{
				$color="green";
			}

$mainttype.=$td;


}else{

$mainttype = $row['Maintenance_Description'];		
$color="blue";
}

 // Stores each database record to an array
 $buildjson = array('title' => "$row[Machine_Name] - $mainttype",'id' => "$id", 'start' => "$sdt",'end' => "$edt", 'allday' => false,'backgroundColor' => "$color");

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
			WHERE SPM_ID='$spm[SPM_ID]' $mid ORDER BY End_Date_Time Desc LIMIT 1;";
//print("$qs<br>");
			$resspm=mysql_query($qs, $cxn) or die(mysql_error($cxn));
			$r=mysql_affected_rows();
			if($r!=0)
			{
				$rfe=mysql_fetch_assoc($resspm);
				$nextspmdate=date('Y-m-d', strtotime($rfe['edt']. ' + '.$spm['SPM_Interval'].' days'));
//				$lastpmdate = strtotime($rfe['edt']);
//				$nextspmdate = date('Y-m-d', mktime(0,0,0,date('m',$lastpmdate),date('d',lastpmdate)+$spm['SPM_Interval'],date('Y',$lastpmdate)));
//				print("machine=$rfe[Machine_Name] lastpm=$rfe[edt] and interval=$spm[SPM_Interval] and next pm=$nextspmdate<br>");
 				$buildjson = array('title' => "$rfe[Machine_Name] - $spm[SPM_Title]", 'start' => "$nextspmdate",'backgroundColor' => "orange", 'allday' => false);
				array_push($jsonArray, $buildjson);
	 		}
 	
}



// Output the json formatted data so that the jQuery call can read it
echo json_encode($jsonArray);
?>