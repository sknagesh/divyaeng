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

///////////////////////

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

///////////////////////////

$q4="SELECT Machine_Name,
SUM(CASE WHEN maint.Maintenance_Type_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS bdm,
SUM(CASE WHEN maint.Maintenance_Type_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS pm,
SUM(CASE WHEN maint.Maintenance_Type_ID=4 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS sm,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS tm From ActivityLog as actl
INNER JOIN Maintenance as maint ON maint.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Maintenance_Type as mtype on mtype.Maintenance_Type_ID=maint.Maintenance_Type_ID
INNER JOIN Machine as m on m.Machine_ID=actl.Machine_ID
WHERE actl.Machine_ID IN(1,2,3,4,5,6,7) AND Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL $interval DAY) AND NOW() GROUP BY m.Machine_ID;";

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
/////////////////////////////////
$q5="SELECT Machine_Name,
SUM(CASE WHEN Activity_ID=8 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS idle
From ActivityLog as actl
INNER JOIN Machine as m on m.Machine_ID=actl.Machine_ID
WHERE m.Machine_ID IN(1,2,3,4,5,6,7) AND Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL $interval DAY) AND NOW() GROUP BY m.Machine_ID;";

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
else
if($drawid=='month')
{
//print("monthwise report");
$proddata = new pData(); 
		$totalhours='';
		$customer='';
		$monthName='';
		$mname='';

for($month=6;$month<=6;$month++)
{
	
$query="SELECT cust.Customer_Name,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
WHERE actl.Machine_ID IN(1,2,3,4,5,6,7) AND month(Start_Date_Time)=$month GROUP BY cust.Customer_ID;";
//print($query);
$res=mysql_query($query) or die(mysql_error());
$r=mysql_affected_rows();

	if($r!=0)
	{
		
	while($row=mysql_fetch_assoc($res))
	{
		if($row['Total']!=''){$t=min2hm($row['Total']);}else{$t='';}
		$totalhours[]=$t;
		$mname=date("F", mktime(0, 0, 0, $month, 10));
		$customer[]=$row['Customer_Name'];
	}
//	$monthName[] = $mname;
	}

}

$proddata->addPoints($totalhours,"Hours");
$proddata->setAxisName(0,"Hours");
$proddata->addPoints($customer,"Customer");
$proddata->setSerieDescription("Customer","Customers");
$proddata->setAbscissa("Customer");
//$proddata->addPoints($monthName,"Month");
print_r($totalhours);
print_r($customer);
print_r($monthName);


$prodPicture = new pImage(1000,300,$proddata);
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$prodPicture->drawFilledRectangle(0,0,700,300);
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$prodPicture->drawGradientArea(0,0,1000,300,DIRECTION_VERTICAL,$Settings);
$prodPicture->drawGradientArea(0,0,1000,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));
$prodPicture->drawRectangle(0,0,999,299,array("R"=>0,"G"=>0,"B"=>0));
$prodPicture->setFontProperties(array("FontName"=>"../fonts/Silkscreen.ttf","FontSize"=>6));
$prodPicture->drawText(10,13,"Production,Set Up and Rework Hours",array("R"=>255,"G"=>255,"B"=>255));
$prodPicture->setFontProperties(array("FontName"=>"../fonts/Forgotte.ttf","FontSize"=>11));
$prodPicture->drawText(250,55,"Total Hours/Customer",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));



$prodPicture->setGraphArea(60,60,950,290);
$prodPicture->drawFilledRectangle(60,60,450,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));

$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
$prodPicture->drawScale($scaleSettings);


$prodPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
$prodPicture->setFontProperties(array("FontName"=>"../fonts/pf_arma_five.ttf","FontSize"=>6));
$prodPicture->drawBarChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO,"Rounded"=>TRUE,"Surrounding"=>60));
$prodPicture->setShadow(FALSE);
$prodPicture->drawLegend(510,205,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

$prodPicture->drawBarChart();
$prodPicture->render("proddata.png");
print('<img src="proddata.png" />');


///////////////////////
/*
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

///////////////////////////

$q4="SELECT Machine_Name,
SUM(CASE WHEN maint.Maintenance_Type_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS bdm,
SUM(CASE WHEN maint.Maintenance_Type_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS pm,
SUM(CASE WHEN maint.Maintenance_Type_ID=4 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS sm,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS tm From ActivityLog as actl
INNER JOIN Maintenance as maint ON maint.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Maintenance_Type as mtype on mtype.Maintenance_Type_ID=maint.Maintenance_Type_ID
INNER JOIN Machine as m on m.Machine_ID=actl.Machine_ID
WHERE actl.Machine_ID IN(1,2,3,4,5,6,7) AND Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL $interval DAY) AND NOW() GROUP BY m.Machine_ID;";

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
/////////////////////////////////
$q5="SELECT Machine_Name,
SUM(CASE WHEN Activity_ID=8 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS idle
From ActivityLog as actl
INNER JOIN Machine as m on m.Machine_ID=actl.Machine_ID
WHERE m.Machine_ID IN(1,2,3,4,5,6,7) AND Start_Date_Time BETWEEN DATE_SUB(NOW(), INTERVAL $interval DAY) AND NOW() GROUP BY m.Machine_ID;";

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

*/

}else{


$query="SELECT cust.Customer_Name,Machine_Name,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Setup,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Rework,
SUM(CASE WHEN Activity_ID=9 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS CMM,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
WHERE ope.Drawing_ID=$drawid AND Batch_ID='$bid' GROUP BY mach.Machine_ID;";

$res=mysql_query($query) or die(mysql_error());
$r=mysql_affected_rows();
//print($query);
	
//			print("<br>Batch Quantity : $es[$l] Nos");
			print("<br><br><table cellspacing=\"1\">");
			print("<tr class=\"t\"><th>Customer</th><th>Machine</th><th>Production</th><th>Set Up</th><th>Rework</th><th>CMM Programing</th><th>Total</th></tr>");

			$c="q";	

			while($row=mysql_fetch_assoc($res))
			{

			if($row['Production']!=''){$p=min2hm($row['Production']);}else{$p='';}
			if($row['Setup']!=''){$s=min2hm($row['Setup']);}else{$s='';}
			if($row['Reowrk']!=''){$rw=min2hm($row['Rework']);}else{$rw='';}
			if($row['CMM']!=''){$cmm=min2hm($row['CMM']);}else{$cmm='';}
			if($row['Total']!=''){$t=min2hm($row['Total']);}else{$t='';}
			print("<tr class=\"$c\"><td>$row[Customer_Name]</td>
									<td>$row[Machine_Name]</td>
									<td>$p</td>
									<td>$s</td>
									<td>$rw</td>
									<td>$cmm</td>
									<td>$t</td>
									</tr>");
			if($c=="q"){$c="s";}else{$c="q";}
	
				}
			
/////////////


$q2="SELECT cust.Customer_Name,Machine_Name,
SUM(CASE WHEN Activity_ID=11 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS FAI,
SUM(CASE WHEN Activity_ID=4 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Fixture,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN NonProduction as nprod ON nprod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN BNo_MI_Challans as bmc on bmc.Batch_ID=nprod.Batch_ID
INNER JOIN MI_Drg_Qty as mdq on mdq.MI_Drg_Qty_Id=bmc.MI_Drg_Qty_ID
INNER JOIN Component as comp on comp.Drawing_ID=mdq.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID
INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
WHERE mdq.Drawing_ID=$drawid AND nprod.Batch_ID='$bid' GROUP BY mach.Machine_ID;";

$r=mysql_query($q2) or die(mysql_error());
$rrr=mysql_affected_rows();
//print($q2);
if($rrr!=0)
{
	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Customer</th>
							<th>Machine</th>							
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
							<td>$row[Machine_Name]</td>
							<td>$fxt</td>
							<td>$fai</td>
							<td>$t</td>
							</tr>");
	if($c=="q"){$c="s";}else{$c="q";}

	}

}

/////////////

$q3="SELECT ope.Operation_Desc,TIME_TO_SEC(ADDTIME(Clamping_Time,Machining_Time)) as tmt,
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
							<th>Act mach. & Cl. Time/Component</th>
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
			if($row['tmt']!=''){$tmt=min2hm($row['tmt']/60);}else{$tmt='';}
	print("<tr class=\"$c\"><td>$row[Operation_Desc]</td>
							<td>$p</td>
							<td>$apt</td>
							<td>$tmt</td>
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
