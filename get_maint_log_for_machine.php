<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_GET);
if(isSet($_GET['mid'])){$mid=$_GET['mid'];}else{$mid="";}
if(isSet($_GET['sdate'])){$sdate=$_GET['sdate'];}else{$sdate="";}
if(isSet($_GET['edate'])){$edate=$_GET['edate'];}else{$edate="";}
if(isSet($_GET['stext'])){$stext=$_GET['stext'];}else{$stext="";}
if(isSet($_GET['mtype'])){$mtype=$_GET['mtype'];}else{$mtype="";}

if($stext!="")
{
	
	$setext="AND (Remarks LIKE '%$stext%' OR 
		Problem_Desc LIKE '%$stext%' OR 
		Maintenance_Desc LIKE '%$stext%')";
}


if($mid!='')
{
	
	$mid="AND actl.Machine_ID='$mid'";
}

if($sdate!='')
{
	
	$sdate="AND Start_Date_Time>='$sdate'";
}

if($edate!='')
{
	
	$edate="AND Start_Date_Time<='$edate'";
}


if($mtype!='')
{
	
	$mty="AND maint.Maintenance_Type_ID='$mtype'";


	if($mtype==1){
		$mr="DEW/PRD/R/01 Rev 1 Dated 01/07/2013";
	}else
	if($mtype==2)
	{
		$mr="DEW/PRD/R/02 Rev 8 Dated 01/7/2013";
	}else
	if($mtype==4)
	{
		$mr="DEW/PRD/R/02 Rev 8 Dated 01/7/2013";
	}else{
	$mr='';
	}
}


	
$query="SELECT actl.Activity_Log_ID,actl.Activity_ID, actl.Machine_ID,Machine_Name,Sch_Prev_Maint_IDs as pm,
		DATE_FORMAT(Start_Date_Time,'%d/%m/%Y %h:%i %p') as sdt,
		DATE_FORMAT(End_Date_Time,'%d/%m/%Y %h:%i %p')as edt,
		TIMEDIFF(End_Date_Time,Start_Date_Time) as td,
		actl.Operator_ID,Remarks,Operator_Name,Maintenance_Description,Problem_Desc,Maintenance_Desc,
		Spares_Used,Service_Engr_Name,
		(SELECT GROUP_CONCAT('/logimages/',Image_Path) FROM ActivityLog_Image as ali 
		WHERE ali.Activity_Log_ID=actl.Activity_Log_ID) as ip
		 FROM ActivityLog as actl  
		INNER JOIN Activity as act ON act.Activity_ID=actl.Activity_ID 
		INNER JOIN Operator as ope ON ope.Operator_ID=actl.Operator_ID
		INNER JOIN Maintenance as maint ON maint.Activity_Log_ID=actl.Activity_Log_ID
		INNER JOIN Machine AS ma ON ma.Machine_ID=actl.Machine_ID
		INNER JOIN Maintenance_Type AS mt ON mt.Maintenance_Type_ID=maint.Maintenance_Type_ID 
		WHERE actl.Activity_ID='5' $mid $sdate $edate $mty $setext 
		  ORDER BY End_Date_Time DESC;";
	
//print("$query<br>");

print("<br><h1>Maintenance Entries for this Machine  $mr</h1><br>");
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$noofrecords=mysql_affected_rows();
if($noofrecords!=0)
{
	$c="q";
print("<table cellspacing=\"1\" cellborder=\"1\" >");
print("<tr class=\"t\" ><th>ID</th><th>Machine</th><th>Activity</th><th>Problem Description</th>
						<th>Maintenance Desc</th><th>Start Date Time</th><th>End Date Time</th>
						<th>Total Time</th><th>Spares Used</th><th>Operator Name</th><th>Maintenance Engineer</th>
						<th>Remarks</th></tr>");
while ($row = mysql_fetch_assoc($resa))
{
		

		if($row['pm']!='')
		{

				$pm=explode(',', $row['pm']);
					$spmdesc='Maintenance Activites carried out are ';
				for($j=0;$j<count($pm);$j++)
				{

					$q="SELECT SPM_Desc as spmd from SPM_Desc WHERE SPM_Desc_ID='$pm[$j]';";
//					print("<br>$q");
					$rpm = mysql_query($q, $cxn) or die(mysql_error($cxn));
					$rspm=mysql_fetch_assoc($rpm);
					$spmdesc.=$rspm['spmd'].', ';

				}

		}else{
			$spmdesc='';
		}



		$id=$row['Activity_Log_ID'];
		$sdt=$row['sdt'];
		$edt=$row['edt'];
		$opename=$row['Operator_Name'];
		$activity=$row['Maintenance_Description'];
		$td=$row['td'];
		$remarks=$row['Remarks'];
		$prob=$row['Problem_Desc'];
		if($spmdesc!='')
		{
			$wd=$spmdesc;
		}else{

		$wd=$row['Maintenance_Desc'];
	}
		$spare=$row['Spares_Used'];
		$me=$row['Service_Engr_Name'];
		$mname=$row['Machine_Name'];
		
				if($row['ip']!='')
		{
			$images=explode(',', $row['ip']);
			
			$ip="<table style=\"width:80px\"><tr><td>$activity<td></tr><tr><td>";
			$y=1;
			for($z=0;$z<count($images);$z++)
			{
				$ip.="<a class=\"pdf\" href=\"$images[$z]\" target=\"_NEW\" title=\"View Image in New Tab\">$y&nbsp;&nbsp;&nbsp;  </a>";
				$y++;
			}
			$ip.="</td></tr></table>";			
		}else{
			
			$ip=$activity;
		}
		

print("<tr class=\"$c\"><td>$id</td><td>$mname</td><td>$ip</td><td>$prob</td><td>$wd</td><td>$sdt</td><td>$edt</td><td align=\"center\">$td</td>
		<td>$spare</td><td>$opename</td><td>$me</td><td>$remarks</td></tr>");
if($c=="q"){$c="s";}else{$c="q";}
}
print("</table>");
}else{print("No Activity Detected For This Machine");}



?>