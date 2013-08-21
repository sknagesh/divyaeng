<?
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$id=$_GET['id'];

if($id!='')
{

$query="SELECT *,DATE_FORMAT(Commited_Date,'%d/%m/%Y') as cdt,DATE_FORMAT(Deliverd_Date,'%d/%m/%Y')as ddt,Drawing_NO,Drawing_Rev,
		Component_Name,Mode_Of_Despatch,DC_NO,Mfg_Batch_NO FROM MO_Drg_Qty as modq
		INNER JOIN Material_Outward as mo ON mo.Material_Outward_ID=modq.Material_Outward_ID
		INNER JOIN BNo_MI_Challans as bmc ON bmc.MI_Drg_Qty_ID=modq.MI_Drg_Qty_ID
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=modq.Drawing_ID
		WHERE modq.Material_Outward_ID='$id';";

//print($query);
		  print("<br><h1>Dispatch Details</h1><br>");
		  $resa = mysql_query($query, $cxn) or die(mysql_error($cxn));

		  print("<table cellspacing=\"1\" cellborder=\"1\" >");
		  print("<tr class=\"t\" ><th>DC No</th><th>Dispatch Date</th><th>Commited Date</th><th>Component Name</th>
		  						<th>Drawing NO</th><th>Batch No</th><th>Dispatch Qty</th><th>Dispatch Mode</th></tr>");
		  while ($row = mysql_fetch_assoc($resa))
		  {
		  		

		  		if($row['ddt']!='00/00/0000'){$ddt=$row['ddt'];}else{$ddt='';}
		  		if($row['cdt']!='00/00/0000'){$cdt=$row['cdt'];}else{$cdt='';}
		  		$compname=$row['Component_Name'];
		  		$drgno=$row['Drawing_NO'].'-'.$row['Drawing_Rev'];
		  		$dqty=$row['Outward_Qty'];
		  		$bno=$row['Mfg_Batch_NO'];
		  		$dmode=$row['Mode_Of_Despatch'];
		  			 			$dcpath='dcpdfs/'.$row['DC_NO'].'.pdf';
		  				$dcpdf="<a class=\"pdf\" href=\"$dcpath\" target=\"_NEW\" title=\"View DC in New Tab\">$row[DC_NO]</a>";
		  		

		  print("<tr><td>$row[DC_NO]</td><td>$ddt</td><td>$cdt</td><td>$compname</td><td>$drgno</td><td>$bno</td><td align=\"center\">$dqty</td><td>$dmode</td></tr>");


			}

			print("<tr><td>DC Copy $dcpdf</td></tr>");


}else{

	print("");
}
?>