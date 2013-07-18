<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['drawingid'])){$drawid=$_GET['drawingid'];}else{$drawid='';}
if(isSet($_GET['bid'])){$bid=$_GET['bid'];}else{$bid="";}
if(isSet($_GET['interval'])){$interval=$_GET['interval'];}else{$interval="60";}
if($drawid=='')
{

$query="SELECT cust.Customer_Name,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Setup,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Rework,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
WHERE actl.Machine_ID IN(1,2,3,4,5,6,7) AND Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL $interval DAY) AND NOW() GROUP BY cust.Customer_ID;";
//print($query);

$q2="SELECT cust.Customer_Name,
SUM(CASE WHEN Activity_ID=11 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS FAI,
SUM(CASE WHEN Activity_ID=4 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Fixture,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN NonProduction as nprod ON nprod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN BNo_MI_Challans as bmc on bmc.Batch_ID=nprod.Batch_ID
INNER JOIN MI_Drg_Qty as mdq on mdq.MI_Drg_Qty_Id=bmc.MI_Drg_Qty_ID
INNER JOIN Component as comp on comp.Drawing_ID=mdq.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
WHERE actl.Machine_ID IN(1,2,3,4,5,6,7) AND Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL $interval DAY) AND NOW() GROUP BY cust.Customer_ID;";

$q4="SELECT Machine_Name,
SUM(CASE WHEN maint.Maintenance_Type_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS bdm,
SUM(CASE WHEN maint.Maintenance_Type_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS pm,
SUM(CASE WHEN maint.Maintenance_Type_ID=4 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS sm,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS tm From ActivityLog as actl
INNER JOIN Maintenance as maint ON maint.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Maintenance_Type as mtype on mtype.Maintenance_Type_ID=maint.Maintenance_Type_ID
INNER JOIN Machine as m on m.Machine_ID=actl.Machine_ID
WHERE actl.Machine_ID IN(1,2,3,4,5,6,7) AND Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL $interval DAY) AND NOW() GROUP BY m.Machine_ID;";

$q5="SELECT Machine_Name,
SUM(CASE WHEN Activity_ID=8 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS idle
From ActivityLog as actl
INNER JOIN Machine as m on m.Machine_ID=actl.Machine_ID
WHERE m.Machine_ID IN(1,2,3,4,5,6,7) AND Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL $interval DAY) AND NOW() GROUP BY m.Machine_ID;";



}
else {
$query="SELECT cust.Customer_Name,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Setup,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Rework,
SUM(CASE WHEN Activity_ID=9 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS CMM,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
WHERE ope.Drawing_ID=$drawid AND Batch_ID='$bid' GROUP BY cust.Customer_ID;";



	
$q2="SELECT cust.Customer_Name,
SUM(CASE WHEN Activity_ID=11 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS FAI,
SUM(CASE WHEN Activity_ID=4 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Fixture,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN NonProduction as nprod ON nprod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN BNo_MI_Challans as bmc on bmc.Batch_ID=nprod.Batch_ID
INNER JOIN MI_Drg_Qty as mdq on mdq.MI_Drg_Qty_Id=bmc.MI_Drg_Qty_ID
INNER JOIN Component as comp on comp.Drawing_ID=mdq.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID
WHERE mdq.Drawing_ID=$drawid AND nprod.Batch_ID='$bid' GROUP BY cust.Customer_ID;";



$q3="SELECT ope.Operation_Desc,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Setup,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Rework,
SUM(CASE WHEN Activity_ID=9 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS CMM,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
WHERE ope.Drawing_ID=$drawid AND Batch_ID='$bid' GROUP BY ope.Operation_ID;";


}

//print($query);
//print($q2);


$res=mysql_query($query) or die(mysql_error());
$r=mysql_affected_rows();

if($drawid=='')
{

	$totalaccounted=0;
	$tproduction=0;
	$tsetup=0;
	$trework=0;
	$tfixture=0;
	$tcmm=0;
	$tfai=0;
	$tidle=0;
	$tmaint=0;
	print("<br>Work Hours for all Customers for last $interval Days");
		
	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Customer</th>
							<th>Production</th>
							<th>Set Up</th>
							<th>Rework</th>
							<th>Total</th>
							</tr>");

	$c="q";	

	while($row=mysql_fetch_assoc($res))
	{
		if($row['Production']!=''){$p=min2hm($row['Production']);$tproduction+=$row['Production'];}else{$p='';}
		if($row['Setup']!=''){$s=min2hm($row['Setup']); $tsetup+=$row['Setup'];}else{$s='';}
		if($row['Rework']!=''){$rw=min2hm($row['Rework']);$trework+=$row['Rework'];}else{$rw='';}
		if($row['Total']!=''){$t=min2hm($row['Total']);}else{$t='';}

		$totalaccounted+=$row['Total'];
	print("<tr class=\"$c\"><td>$row[Customer_Name]</td>
							<td>$p</td>
							<td>$s</td>
							<td>$rw</td>
							<td>$t</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}
	print("</table>");		
$r=mysql_query($q2) or die(mysql_error());



	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Customer</th>
							<th>Fixture Work</th>
							<th>FAI</th>
							<th>Total</th>
							</tr>");

	$c="q";	

	while($row=mysql_fetch_assoc($r))
	{
		if($row['FAI']!=''){$fai=min2hm($row['FAI']);$tfai+=$row['FAI'];}else{$fai='';}
		if($row['Fixture']!=''){$fxt=min2hm($row['Fixture']);$tfixture+=$row['Fixture'];}else{$fxt='';}
		if($row['Total']!=''){$t=min2hm($row['Total']);}else{$t='';}
		$totalaccounted+=$row['Total'];
	print("<tr class=\"$c\"><td>$row[Customer_Name]</td>
							<td>$fxt</td>
							<td>$fai</td>
							<td>$t</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}
print("</table>");


$r4=mysql_query($q4) or die(mysql_error());
	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Machine</th>
							<th>Break Down Maintenence</th>
							<th>Preventive Maintenance</th>
							<th>Scheduled Maintenance</th>
							<th>Total</th>
							</tr>");

	$c="q";	

	while($row=mysql_fetch_assoc($r4))
	{
		if($row['bdm']!=''){$bdm=min2hm($row['bdm']);$tmaint+=$row['bdm'];}else{$bdm='';}
		if($row['pm']!=''){$pm=min2hm($row['pm']);$tmaint+=$row['pm'];}else{$pm='';}
		if($row['sm']!=''){$sm=min2hm($row['sm']);$tmaint+=$row['sm'];}else{$sm='';}
		if($row['tm']!=''){$tm=min2hm($row['tm']);}else{$tm='';}
		$totalaccounted+=$row['tm'];
	print("<tr class=\"$c\"><td>$row[Machine_Name]</td>
							<td>$bdm</td>
							<td>$pm</td>
							<td>$sm</td>
							<td>$tm</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}
print("</table>");

$r5=mysql_query($q5) or die(mysql_error());
	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Machine</th>
							<th>Idle Time</th>
							</tr>");

	$c="q";	

	while($row=mysql_fetch_assoc($r5))
	{
		if($row['idle']!=''){$idle=min2hm($row['idle']);$tidle+=$row['idle'];}else{$idle='';}
		if($row['ti']!=''){$ti=min2hm($row['ti']);}else{$ti='';}
		$totalaccounted+=$row['idle'];
	print("<tr class=\"$c\"><td>$row[Machine_Name]</td>
							<td>$idle</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}
print("</table>");
$availablehours=$interval*24*7;
$totalaccounted=min2hm($totalaccounted);
$tproduction=min2hm($tproduction);$tper=round((($tproduction/$totalaccounted)*100),2);
$tsetup=min2hm($tsetup);$tsper=round((($tsetup/$totalaccounted)*100),2);
$trework=min2hm($trework);$trwper=round((($trework/$totalaccounted)*100),2);
$tfixture=min2hm($tfixture);$tfxtper=round((($tfixture/$totalaccounted)*100),2);
$tcmm=min2hm($tcmm);$tcmmper=round((($tcmm/$totalaccounted)*100),2);
$tfai=min2hm($tfai);$tfaiper=round((($tfai/$totalaccounted)*100),2);
$tmaint=min2hm($tmaint);$tmaintper=round((($tmaint/$totalaccounted)*100),2);
$tidle=min2hm($tidle);$tidleper=round((($tidle/$totalaccounted)*100),2);

//print('<p style="font size:12 color:green">Total Available hours including holidays from 7+1 machines are '.$availablehours.' and Total Accounted Hours are '.$totalaccounted.'</p>');
	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Heading</th>
							<th>Total Time</th>
							<th>Percentage</th>
							</tr>");

	print("<tr class=\"q\"><td>Production</td><td>$tproduction</td><td>$tper</td></tr>");
	print("<tr class=\"s\"><td>Set Up</td><td>$tsetup</td><td>$tsper</td></tr>");
	print("<tr class=\"q\"><td>Rework</td><td>$trework</td><td>$trwper</td></tr>");
	print("<tr class=\"s\"><td>Fixture Work</td><td>$tfixture</td><td>$tfxtper</td></tr>");
	print("<tr class=\"s\"><td>FAI</td><td>$tfai</td><td>$tfaiper</td></tr>");
	print("<tr class=\"q\"><td>Maintenance</td><td>$tmaint</td><td>$tmaintper</td></tr>");
	print("<tr class=\"s\"><td>Idle</td><td>$tidle</td><td>$tidleper</td></tr>");
	print("<tr class=\"q\"><td>Total Accounted</td><td>$totalaccounted</td><td>100%</td></tr>");
	print("<tr class=\"s\"><td>Total Available</td><td>$availablehours</td></tr>");
}
else {

		if($r!=0)
		{
			
	
//			print("<br>Batch Quantity : $es[$l] Nos");
			print("<br><br><table cellspacing=\"1\">");
			print("<tr class=\"t\"><th>Customer</th><th>Production</th><th>Set Up</th><th>Rework</th><th>CMM Programing</th><th>Total</th></tr>");

			$c="q";	

			while($row=mysql_fetch_assoc($res))
			{

			if($row['Production']!=''){$p=min2hm($row['Production']);}else{$p='';}
			if($row['Setup']!=''){$s=min2hm($row['Setup']);}else{$s='';}
			if($row['Reowrk']!=''){$rw=min2hm($row['Rework']);}else{$rw='';}
			if($row['CMM']!=''){$cmm=min2hm($row['CMM']);}else{$cmm='';}
			if($row['Total']!=''){$t=min2hm($row['Total']);}else{$t='';}
			print("<tr class=\"$c\"><td>$row[Customer_Name]</td>
									<td>$p</td>
									<td>$s</td>
									<td>$rw</td>
									<td>$cmm</td>
									<td>$t</td>
									</tr>");
			if($c=="q"){$c="s";}else{$c="q";}
	
				}
			}

$r=mysql_query($q2) or die(mysql_error());
$rrr=mysql_affected_rows();

if($rrr!=0)
{
	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Customer</th>
							<th>Fixture Work</th>
							<th>FAI</th>
							<th>Total</th>
							</tr>");

	$c="q";	

	while($row=mysql_fetch_assoc($r))
	{
		if($row['FAI']!=''){$fai=min2hm($row['FAI']);}else{$fai='';}
		if($row['Fixture']!=''){$fxt=min2hm($row['Fixture']);}else{$fxt='';}
		if($row['Total']!=''){$t=min2hm($row['Total']);}else{$t='';}
	print("<tr class=\"$c\"><td>$row[Customer_Name]</td>
							<td>$fxt</td>
							<td>$fai</td>
							<td>$t</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}

}



$r3=mysql_query($q3) or die(mysql_error());
$rrr3=mysql_affected_rows();

if($rrr3!=0)
{

	$qb="SELECT SUM(Qty_In_Batch) as qty FROM BNo_MI_Challans WHERE Batch_ID='$bid';";
//	print($qb);
	$rb=mysql_query($qb) or die(mysql_error());
	$rowb=mysql_fetch_assoc($rb);
	$qty=$rowb['qty'];


	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Operation</th>
							<th>Production</th>
							<th>Avg Time/Component</th>
							<th>Setup</th>
							<th>Rework</th>
							<th>CMM</th>
							<th>Total</th>
							</tr>");

	$c="q";	

	while($row=mysql_fetch_assoc($r3))
	{
			if($row['Production']!=''){$p=min2hm($row['Production']);$apt=min2hm($row['Production']/$qty);}else{$p='';}
			if($row['Setup']!=''){$s=min2hm($row['Setup']);}else{$s='';}
			if($row['Reowrk']!=''){$rw=min2hm($row['Rework']);}else{$rw='';}
			if($row['CMM']!=''){$cmm=min2hm($row['CMM']);}else{$cmm='';}
			if($row['Total']!=''){$t=min2hm($row['Total']);}else{$t='';}
	print("<tr class=\"$c\"><td>$row[Operation_Desc]</td>
							<td>$p</td>
							<td>$apt</td>
							<td>$s</td>
							<td>$rw</td>
							<td>$cmm</td>
							<td>$t</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}

}


}
?>