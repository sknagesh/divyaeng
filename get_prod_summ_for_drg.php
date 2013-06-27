<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['drawingid'])){$drawid=$_GET['drawingid'];}else{$drawid='';}
if(isSet($_GET['bid'])){$bid=$_GET['bid'];}else{$bid="";}
if($drawid=='')
{

$query="SELECT cust.Customer_Name,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Setup,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Rework,
SUM(CASE WHEN Activity_ID=14 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS CMM,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID WHERE Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW() GROUP BY cust.Customer_ID;";

$q2="SELECT cust.Customer_Name,
SUM(CASE WHEN Activity_ID=11 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS FAI,
SUM(CASE WHEN Activity_ID=4 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Fixture,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN NonProduction as nprod ON nprod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN BNo_MI_Challans as bmc on bmc.Batch_ID=nprod.Batch_ID
INNER JOIN MI_Drg_Qty as mdq on mdq.MI_Drg_Qty_Id=bmc.MI_Drg_Qty_ID
INNER JOIN Component as comp on comp.Drawing_ID=mdq.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID WHERE Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW() GROUP BY cust.Customer_ID;";



}
else {
$query="SELECT cust.Customer_Name,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Setup,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Rework,
SUM(CASE WHEN Activity_ID=14 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS CMM,
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


}

//print($query);
//print($q2);


$res=mysql_query($query) or die(mysql_error());
$r=mysql_affected_rows();

if($drawid=='')
{
	print("<br>Work Hours for all Customers for last 60 Days");
		
	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Customer</th>
							<th>Production</th>
							<th>Set Up</th>
							<th>Rework</th>
							<th>CMM</th>
							<th>Total</th>
							</tr>");

	$c="q";	

	while($row=mysql_fetch_assoc($res))
	{
		if($row['Production']!=''){$p=min2hm($row['Production']);}else{$p='';}
		if($row['Setup']!=''){$s=min2hm($row['Setup']);}else{$s='';}
		if($row['Reowrk']!=''){$rw=min2hm($row['Rework']);}else{$rw='';}
		if($row['CMM']!=''){$cmm=min2hm($row['CMM']);}else{$cmm='';}
		if($row['FAI']!=''){$fai=min2hm($row['FAI']);}else{$fai='';}
		if($row['Fixture']!=''){$fxt=min2hm($row['Fixture']);}else{$fxt='';}
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
		if($row['FAI']!=''){$fai=min2hm($row['FAI']);}else{$fai='';}
		if($row['Fixture']!=''){$fxt=min2hm($row['Fixture']);}else{$fxt='';}
		if($row['Total']!=''){$t=min2hm($row['Total']);}else{$t='';}
	print("<tr class=\"$c\"><td>$row[Customer_Name]</td>
							<td>$fai</td>
							<td>$fxt</td>
							<td>$t</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}


	
}
else {

		if($r!=0)
		{
			
	
//			print("<br>Batch Quantity : $es[$l] Nos");
			print("<br><br><table cellspacing=\"1\">");
			print("<tr class=\"t\"><th>Customer</th><th>Production</th><th>Set Up</th><th>Rework</th><th>CMM</th><th>Total</th></tr>");

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
							<td>$fai</td>
							<td>$fxt</td>
							<td>$t</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}

}


}
?>