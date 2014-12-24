<?php
include('dewdb.inc');

$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['sdate'])){$sdate=$_GET['sdate'];}else{$sdate="";}
if(isSet($_GET['edate'])){$edate=$_GET['edate'];}else{$edate="";}
$machineid=array(1,2,3,4,5,6,7,18);

if(($sdate=='')&&($edate==''))
{

$sdate=date('Y/m/d 0:0:0',strtotime("-1 days"));
$edate=date('Y/m/d 23:59:59',strtotime("-1 days"));
}

$x=0;
$j=0;
while($x<count($machineid))
{


//get hours started and finished with in day
$query="SELECT Machine_Name,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Setup,
SUM(CASE WHEN Activity_ID=16 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Proving,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Rework,
SUM(CASE WHEN Activity_ID=5 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Maintenance,
SUM(CASE WHEN Activity_ID=8 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS M_Stop,
SUM(CASE WHEN Activity_ID=11 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS FA,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
RIGHT OUTER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID 
WHERE actl.Machine_ID=$machineid[$x] AND Start_Date_Time>='$sdate' AND End_Date_Time<='$edate' GROUP BY mach.Machine_ID;";
//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows();
	while($row=mysql_fetch_assoc($res))
	{
if($row['Production']!=''){$pb=$row['Production']/60;}else{$pb=0;}
if($row['Setup']!=''){$sb=$row['Setup']/60;}else{$sb=0;}
if($row['Proving']!=''){$prb=$row['Proving']/60;}else{$prb=0;}
if($row['Rework']!=''){$rwb=$row['Rework']/60;}else{$rwb=0;}
if($row['Maintenance']!=''){$mb=$row['Maintenance']/60;}else{$mb=0;}
if($row['M_Stop']!=''){$msb=$row['M_Stop']/60;}else{$msb=0;}
if($row['FA']!=''){$fab=$row['FA']/60;}else{$fab=0;}
if($row['Total']!=''){$tb=$row['Total']/60;}else{$tb=0;}
$between[$j]=array($row['Machine_Name'],$pb,$sb,$prb,$rwb,$mb,$msb,$fab,$tb);
	
//	print("prodb=$pb supb=$sb provb=$prb rwrkb=$rwb mainb=$mb stopb=$msb fab=$fab totb=$tb<p>");

}

$j++;
$x++;
	}

//get hours started early but finished with in day
$x=0;
$k=0;
while($x<count($machineid))
{

$querye="SELECT Machine_Name,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,'$sdate',End_Date_Time) END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,'$sdate',End_Date_Time) END) AS Setup,
SUM(CASE WHEN Activity_ID=16 THEN TIMESTAMPDIFF(minute,'$sdate',End_Date_Time) END) AS Proving,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,'$sdate',End_Date_Time) END) AS Rework,
SUM(CASE WHEN Activity_ID=5 THEN TIMESTAMPDIFF(minute,'$sdate',End_Date_Time) END) AS Maintenance,
SUM(CASE WHEN Activity_ID=8 THEN TIMESTAMPDIFF(minute,'$sdate',End_Date_Time) END) AS M_Stop,
SUM(CASE WHEN Activity_ID=11 THEN TIMESTAMPDIFF(minute,'$sdate',End_Date_Time) END) AS FA,
SUM(TIMESTAMPDIFF(minute,'$sdate',End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID 
WHERE actl.Machine_ID=$machineid[$x] AND Start_Date_Time<'$sdate' AND End_Date_Time>'$sdate' GROUP BY mach.Machine_ID;";
//print($querye);
//print('<p>');



$rese=mysql_query($querye) or die(mysql_error());
$re=mysql_num_rows($rese);

if($re!=0)
{

	while($rowe=mysql_fetch_assoc($rese))
	{
		if($rowe['Production']!=''){$pe=$rowe['Production']/60;}else{$pe=0;}
		if($rowe['Setup']!=''){$se=$rowe['Setup']/60;}else{$se=0;}
		if($rowe['Proving']!=''){$pre=$rowe['Proving']/60;}else{$pre=0;}
		if($rowe['Rework']!=''){$rwe=$rowe['Rework']/60;}else{$rwe=0;}
		if($rowe['Maintenance']!=''){$me=$rowe['Maintenance']/60;}else{$me=0;}
		if($rowe['M_Stop']!=''){$mse=$rowe['M_Stop']/60;}else{$mse=0;}
		if($rowe['FA']!=''){$fae=$rowe['FA']/60;}else{$fae=0;}
		if($rowe['Total']!=''){$te=$rowe['Total']/60;}else{$te=0;}

		$early[$k]=array($pe,$se,$pre,$rwe,$me,$mse,$fae,$te);

	}
}else
{
$pe=0;$se=0;$re=0;$rwe=0;$me=0;$mse=0;$fae=0;$te=0;

$early[$k]=array($pe,$se,$pre,$rwe,$me,$mse,$fae,$te);
}
//print("prode=$pe supe=$se prove=$pre rwrke=$rwe maine=$me stope=$mse fae=$fae tote=$te<p>");
$x++;
$k++;
	}

$z=0;
$l=0;
while($z<count($machineid))
{
//get hours started during day but finished next day
$queryl="SELECT Machine_Name,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,'$edate') END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,'$edate') END) AS Setup,
SUM(CASE WHEN Activity_ID=16 THEN TIMESTAMPDIFF(minute,Start_Date_Time,'$edate') END) AS Proving,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,'$edate') END) AS Rework,
SUM(CASE WHEN Activity_ID=5 THEN TIMESTAMPDIFF(minute,Start_Date_Time,'$edate') END) AS Maintenance,
SUM(CASE WHEN Activity_ID=8 THEN TIMESTAMPDIFF(minute,Start_Date_Time,'$edate') END) AS M_Stop,
SUM(CASE WHEN Activity_ID=11 THEN TIMESTAMPDIFF(minute,Start_Date_Time,'$edate') END) AS FA,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,'$edate')) AS Total From ActivityLog as actl
INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID 
WHERE actl.Machine_ID=$machineid[$z] AND Start_Date_Time<'$edate' AND End_Date_Time>'$edate' GROUP BY mach.Machine_ID;";
//print($queryl);



$resl=mysql_query($queryl) or die(mysql_error());
$rl=mysql_num_rows($resl);

if($rl!=0)
{
	while($rowl=mysql_fetch_assoc($resl))
	{
		if($rowl['Production']!=''){$pl=$rowl['Production']/60;}else{$pl=0;}
		if($rowl['Setup']!=''){$sl=$rowl['Setup']/60;}else{$sl=0;}
		if($rowl['Proving']!=''){$prl=$rowl['Proving']/60;}else{$prl=0;}
		if($rowl['Rework']!=''){$rwl=$rowl['Rework']/60;}else{$rwl=0;}
		if($rowl['Maintenance']!=''){$ml=$rowl['Maintenance']/60;}else{$ml=0;}
		if($rowl['M_Stop']!=''){$msl=$rowl['M_Stop']/60;}else{$msl=0;}
		if($rowl['FA']!=''){$fal=$rowl['FA']/60;}else{$fal=0;}
		if($rowl['Total']!=''){$tl=$rowl['Total']/60;}else{$tl=0;}

		$late[$l]=array($pl,$sl,$prl,$rwl,$ml,$msl,$fal,$tl);

//print("prodl=$pl supl=$sl provl=$prl rwrkl=$rwl mainl=$ml stopl=$msl fal=$fal totl=$tl<p>");
	}
}else{
		$pl=0;$sl=0;$prl=0;$rwl=0;$ml=0;$msl=0;$fal=0;$tl=0;
		$late[$l]=array($pl,$sl,$prl,$rwl,$ml,$msl,$fal,$tl);	
}
$l++;
$z++;

}

	print("<br>Activity Log from $sdate upto $edate for All Machines");
		
	print("<br><br><table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Machine</th><th>Production</th><th>Set Up</th><th>Prog. Proving</th><th>Rework</th><th>Maintenance</th>
							<th>Machine Stop</th><th>FA</th><th>Total</th></tr>");
/*
print_r($between);
print('<p>');
print_r($early);
print('<p>');
print_r($late);
print('<p>');
*/
	$c="q";	
	$i=0;
	$nr=0;
while($nr<count($between))
{

	$pt=round($between[$i][1]+$early[$i][0]+$late[$i][0],2);
	$st=round($between[$i][2]+$early[$i][1]+$late[$i][1],2);
	$ppt=round($between[$i][3]+$early[$i][2]+$late[$i][2],2);
	$rwt=round($between[$i][4]+$early[$i][3]+$late[$i][3],2);
	$mt=round($between[$i][5]+$early[$i][4]+$late[$i][4],2);
	$mst=round($between[$i][6]+$early[$i][5]+$late[$i][5],2);
	$fat=round($between[$i][7]+$early[$i][6]+$late[$i][6],2);
	$tt=round($between[$i][8]+$early[$i][7]+$late[$i][7],2);

	print("<tr class=\"$c\"><td>".$between[$nr][0]."</td><td>$pt</td><td>$st</td><td>$ppt</td><td>$rw</td><td>$mt</td>
							<th>$mst</td><td>$fat</td><td>$tt</td></tr>");

	if($c=="q"){$c="s";}else{$c="q";}
$i++;
$nr++;
}




	print("</table>");		




?>
