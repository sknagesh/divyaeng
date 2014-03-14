<?
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$id=$_GET['id'];
//print($id);

if($id!='')
{

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
		WHERE actl.Activity_Log_ID='$id';";


		  print("<br><h1>Maintenance Details</h1><br>");
		  $resa = mysql_query($query, $cxn) or die(mysql_error($cxn));

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
		  		

		  print("<tr><td>$id</td><td>$mname</td><td>$ip</td><td>$prob</td><td>$wd</td><td>$sdt</td><td>$edt</td><td align=\"center\">$td</td>
		  		<td>$spare</td><td>$opename</td><td>$me</td><td>$remarks</td></tr>");


}
}else{

	print("");
}


?>