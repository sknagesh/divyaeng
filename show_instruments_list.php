<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$type=$_GET['type'];

$query="SELECT *,DATE_FORMAT(Calibration_Date,'%d-%m-%Y') as cd FROM Instrument WHERE Show_In_List=1 ORDER BY Instrument_ID ASC;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{

	if($type=='list')
	{


		print("<h2>List Of Measuring Instruments And Gages</h2>");
		print("<h3>Document. Ref: DEW/QA/D/01 Issue NO.: 06  Date: 01-06-2013</h3>");

		print("<table border=\"1\" cellspacing=\"1\">");
		print("<tr><th>Serial No</th><th>Description</th><th>Make</th><th>Range</th>
		<th>Least Count</th><th>Calibration Frequency in Months</th><th>Remarks</th></tr>");
		while($row=mysql_fetch_assoc($res))
			{

				print("<tr><td>$row[Instrument_SLNO]</td><td>$row[Instrument_Description]</td>
			<td>$row[Make]</td><td>$row[Instrument_Range]</td><td>$row[Least_Count] mm</td>
			<td>$row[Calibration_Frequency]</td><td>$row[Remarks]</td></tr>");
	
			}
		print("</table>");

	}
	else
	if($type="history")
	{

		print("<h2>Calibration History Card</h2>");
		print("<h3>Document. Ref: DEW/QA/R/01 Issue NO.: 01  Date: 01-05-2012</h3>");



		print("<table border=\"1\" cellspacing=\"1\">");
		print("<tr><th>Serial No</th><th>Description</th><th>Make</th><th>Range</th>
		<th>Least Count</th><th>Calibration Date</th><th>Next Calibration Due Date</th><th>Remarks</th></tr>");
			while($row=mysql_fetch_assoc($res))
		{

					if($row['Calibration_Date']!='')
					{
					$cd = strtotime($row['Calibration_Date']);
	  				$ncd = date('Y-m-d', mktime(0,0,0,date('m',$cd)+$row['Calibration_Frequency'],date('d',$cd-1),date('Y',$cd)));
					$ncdisplay = date('d-m-Y', mktime(0,0,0,date('m',$cd)+$row['Calibration_Frequency'],date('d',$cd-1),date('Y',$cd)));
  					$today_start = strtotime('today');
					$monthperiod = strtotime('+1 month');
					$duedate = strtotime($ncd);

//print("<br>today= $today_start, 1 month= $monthperiod, duedate=$duedate");
						if ($duedate > $monthperiod)
  							{

							$s='style="background-color: lightgreen"';
 							$c='';
  							}
  							if ($duedate < $monthperiod)
  							{
							$s='style="background-color: orange"';
							$c="Calibration Due This Month";
  							}
  							if ($duedate < $today_start)
  							{
							$s='style="background-color: red"';
							$c="Calibration Over Due";
  							}

					}else
  					{
  					$ncd='';$ncdisplay=''; $c='';$s='style="background-color: white"';
  					} 

	print("<tr><td>$row[Instrument_SLNO]</td><td>$row[Instrument_Description]</td>
			<td>$row[Make]</td><td>$row[Instrument_Range]</td><td>$row[Least_Count]</td>
			<td>$row[cd]</td><td $s>$ncdisplay $c</td><td>$row[Remarks]</td></tr>");
	

		}

		print("</table>");
		}
}
else {
	print("No Instruments Added");
}

?>