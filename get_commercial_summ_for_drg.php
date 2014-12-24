<?php
include('dewdb.inc');
include("../pChart2.1.3/class/pData.class.php");
include("../pChart2.1.3/class/pDraw.class.php");
include("../pChart2.1.3/class/pImage.class.php");

$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['drawingid'])){$drawid=$_GET['drawingid'];}else{$drawid='';}
if(isSet($_GET['bid'])){$bid=$_GET['bid'];}else{$bid="";}
if(isSet($_GET['interval'])){$interval=$_GET['interval'];}else{$interval="60";}
if(isSet($_GET['sdate'])){$sdate=$_GET['sdate'];}else{$sdate="";}
if(isSet($_GET['edate'])){$edate=$_GET['edate'];}else{$edate="";}

if(($sdate=='')&&($edate==''))
{

	$cri="AND Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL $interval DAY) AND NOW()";
}else{

	
	$cri="AND Start_Date_Time>='$sdate' AND End_Date_Time<='$edate'";

	$interval=(strtotime($edate)-strtotime($sdate))/(60 * 60 * 24);
	
//	print("calculated interval=$interval");

}




if($drawid=='')
{


$query="SELECT cust.Customer_Name,
SUM(CASE WHEN Activity_ID=1 THEN (Quantity*P_Of_Op) END) AS Production,
SUM(CASE WHEN Activity_ID=16 THEN (Quantity*P_Of_Op) END) AS Proving,
SUM(Quantity) as qty
From ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
WHERE actl.Machine_ID IN(1,2,3,4,5,6,7,18) $cri GROUP BY cust.Customer_ID;";
//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_affected_rows();

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
							<th>Production Earnings</th>
							<th>Total Components</th>
							</tr>");

	$c="q";	

	while($row=mysql_fetch_assoc($res))
	{
		$te=$row['Production']+$row['Proving'];
	print("<tr class=\"$c\"><td>$row[Customer_Name]</td>
							<td>$te</td>
							<td>$row[qty]</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}
	print("</table>");		

///////////////////////////

/////////////////////////////////


$q5="SELECT Machine_Name,
SUM(CASE WHEN Activity_ID=1 THEN (Quantity*P_Of_Op) END) AS Production,
SUM(CASE WHEN Activity_ID=16 THEN (Quantity*P_Of_Op) END) AS Proving,
SUM(Quantity) as qty
From ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
WHERE actl.Machine_ID IN(1,2,3,4,5,6,7,18) $cri GROUP BY mach.Machine_ID;";

$r5=mysql_query($q5) or die(mysql_error());
	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Machine</th>
							<th>Qty</th>
							<th>Earnings</th>
							</tr>");

	$c="q";	
$pe=0;
	while($row=mysql_fetch_assoc($r5))
	{
	print("<tr class=\"$c\"><td>$row[Machine_Name]</td>
							<td>$row[qty]</td>
							<td>$pe</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}
print("</table>");


}
else{


	$qbq="SELECT SUM(Qty_In_Batch) as qty FROM BNo_MI_Challans WHERE Batch_ID='$bid';";
//	print($qb);
	$rbq=mysql_query($qbq) or die(mysql_error());
	$rowbq=mysql_fetch_assoc($rbq);
	$qtyq=$rowbq['qty'];


$query="SELECT cust.Customer_Name,Machine_Name,
SUM(CASE WHEN Activity_ID=1 THEN (Quantity*P_Of_Op) END) AS Production,
SUM(CASE WHEN Activity_ID=16 THEN (Quantity*P_Of_Op) END) AS Proving,
SUM(Quantity) as qty FROM ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
WHERE ope.Drawing_ID=$drawid AND Batch_ID='$bid' AND mach.Machine_ID!=9 GROUP BY mach.Machine_ID;";

$res=mysql_query($query) or die(mysql_error());
$r=mysql_affected_rows();
//print($query);
	
//			print("<br>Batch Quantity : $es[$l] Nos");
			print("<br><br><table cellspacing=\"1\">");
			print("<tr class=\"t\"><th>Machines used</th><th>Production Earnings</th><th>Qty Produced</th></tr>");

			$c="q";	
			while($row=mysql_fetch_assoc($res))
			{

				$tpe=$row['Production']+$row['Proving'];
			print("<tr class=\"$c\"><td>$row[Machine_Name]</td>
									<td>$tpe</td>
									<td>$row[qty]</td>
									</tr>");

			if($c=="q"){$c="s";}else{$c="q";}
	
				}
			


}
?>
