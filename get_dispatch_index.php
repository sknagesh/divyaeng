<?
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

///*dispatch details summary*///////

$query="SELECT DATE_FORMAT(mo.date,'%M,%Y') as m,DATE_FORMAT(mo.date,'%m,%Y') as mm,DATE_FORMAT(bn.Deposit_Date,'%M,%Y') as dd,
		SUM(CASE WHEN DATEDIFF(Commited_Date,Deposit_Date)<0 THEN 1 END) AS late,
		SUM(CASE WHEN DATEDIFF(Deposit_Date,Commited_Date)<=0 THEN 1 END) AS ontime,
		SUM(CASE WHEN mo.Date!='' THEN 1 END) AS total FROM MO_Drg_Qty as modq
		INNER JOIN Material_Outward as mo ON mo.Material_Outward_ID=modq.Material_Outward_ID
		INNER JOIN BNo_MI_Challans as bmc ON bmc.MI_Drg_Qty_ID=modq.MI_Drg_Qty_ID
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=modq.Drawing_ID
		WHERE mo.Date<NOW() AND comp.Customer_ID=1 GROUP BY YEAR(mo.Date),MONTH(mo.Date);";


		  $res1 = mysql_query($query, $cxn) or die(mysql_error($cxn));
		  $noofdispatches=mysql_num_rows($res1);
		  $latedispatches=0;
		  $ontimedispatches=0;
		  print("<p>");
		  	print("<table cellspacing=\"1\">");
	print("<tr class=\"t\"><th>Details</th><th>Month and Year</th>
							<th>Total Dispatches</th>
							<th>On Time</th><th>Late</th><th>On Time Delivery %</th></tr>");
	$i=0;
	$c='q';
		  while ($row = mysql_fetch_assoc($res1))
		  {
		  	$dper=round((($row['ontime']/$row['total'])*100),2);

	print("<tr class=\"$c\"><td><input type=\"radio\" name=\"dinfo\" class=\"dinfo\" id=\"dinfo[$i]\" value=\"$row[mm]\"></input></td>
							<td>$row[m]</td>
							<td>$row[total]</td>
							<td>$row[ontime]</td>
							<td>$row[late]</td>
							<td>$dper</td></tr>
							<tr><td colspan=\"5\"><div id=\"$i\"></div></td></tr>");
	if($c=="q"){$c="s";}else{$c="q";}
	$i++;

  				
		  }

//////





?>