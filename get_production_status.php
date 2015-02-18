<?php
ini_set("memory_limit",-1);
include('dewdb.inc');
$tasum='';

$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$x=1;
$y=0;
$totalhours=0;

		$q1="SELECT DISTINCT Mfg_Batch_NO,Component_Name,Drawing_NO,comp.Drawing_ID,bn.Batch_ID,
			(SELECT SUM(Qty_In_Batch) FROM BNo_MI_Challans AS bmc WHERE bmc.Batch_ID=bn.Batch_ID) AS qty 
			FROM Batch_NO AS bn INNER JOIN BNo_MI_Challans AS bmc2 ON bmc2.Batch_ID=bn.Batch_ID
			INNER JOIN MI_Drg_Qty AS midq ON midq.MI_Drg_Qty_ID=bmc2.MI_Drg_Qty_ID 
			INNER JOIN Component AS comp ON comp.Drawing_ID=midq.Drawing_ID WHERE Batch_Under_Progress=1";

		$r1=mysql_query($q1) or die(mysql_error());
		print("<table cellspacing=\"1\" border=\"1\">");
		print("<tr><th>Drawing No and Name</th><th>Operation Description</th><th>Qty Planned</th><th>Qty Machined</th><th>% Complete</th></tr>");

		while($re1=mysql_fetch_assoc($r1))
		{

						$q2="SELECT Setup_Time,ADDTIME(Clamping_Time,Machining_Time) as tt,Operation_Desc,Operation_ID,
						(".$re1['qty']."*TIME_TO_SEC(ADDTIME(Clamping_Time,Machining_Time))) as Total_Time 
						FROM Operation WHERE Drawing_ID=".$re1['Drawing_ID']." AND In_Tool_List=1 AND In_Op_list!=1 ORDER BY Operation_Desc ASC;";
//						print("$q2");
						$r2=mysql_query($q2) or die(mysql_error());

						while($re2=mysql_fetch_assoc($r2))
						{

							$q3="SELECT SUM(Quantity) AS pqty From ActivityLog as actl
									INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
									INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
									INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
									INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
									WHERE ope.Operation_ID=$re2[Operation_ID] AND Batch_ID='$re1[Batch_ID]' AND In_Tool_List=1 AND 
									ope.In_Tool_List!=0 AND ope.In_Op_list!=1 AND actl.Activity_ID in(1,2,16)";
								//print("$q3");
								$r3=mysql_query($q3) or die(mysql_error());
								$re3=mysql_fetch_assoc($r3);
								$time=round(($re2['Total_Time']/3600),2);
								$timeconsumed=round(($re3['Total']/60),2);
								$pc=round((($re3['pqty']/$re1['qty'])*100),2);
							if($time!=0)
							{
								print("<tr><td>$re1[Drawing_NO]-$re1[Component_Name]</td><td>$re2[Operation_Desc]</td><td>$re1[qty]</td><td>$re3[pqty]</td><td>$pc</td></tr>");
							}
						
						}

		}
/*


		$r4=mysql_query($q4) or die(mysql_error());
		$rrr4=mysql_affected_rows();

		if($rrr4!=0)
			{
		//print("printing average timing details<p>");
			$x=$y;
			$tahours=0;
				while($row4=mysql_fetch_assoc($r4))
					{
					if($row4['Production']!=''){$p=$row4['Production'];}else{$p='';}
					if($row4['Setup']!=''){$s=$row4['Setup'];}else{$s='';}
					if($row4['Proving']!=''){$pp=$row4['Proving'];}else{$pp='';}
					if($row4['Rework']!=''){$rw=min2hm($row4['Rework']);}else{$rw='';}
					if($row4['CMM']!=''){$cmm=min2hm($row4['CMM']);}else{$cmm='';}
					if($row4['Total']!=''){$t=min2hm($row4['Total']);}else{$t='';}
					if($row4['tmt']!=''){$tmt=min2hm($row4['tmt']/60);}else{$tmt='';}

					$w=$y-2;
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$x,round($p/$bqty,2));
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$x,'=((C'.$x.'*B'.$w.')+B'.$x.')/60');
					$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getNumberFormat()->setFormatCode('0.0');
					$spp=$s+$pp;
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$x,$spp);
					$tahours+=(($p/$bqty)*$drgqty)/60;
					$x++;
					}

				//print("finished printing average timing details<p>");
			}



		$styleArray = array(
    		'font'  => array(
        	'bold'  => true,
        	'color' => array('rgb' => 'FF0000'),
        	'size'  => 12,
        	'name'  => 'Verdana'
    	));
		//print("printing part total timing details<p>");
		$z=$y-2;
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$z,$drgqty);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$z)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$z,'Total Hours');
		$styleArray = array(
    		'font'  => array(
        	'bold'  => true,
        	'color' => array('rgb' => 'GREEN'),
        	'size'  => 12,
        	'name'  => 'Verdana'
    		));

		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$z,'=SUM(E'.$y.':E'.$x.')');
		$objPHPExcel->getActiveSheet()->getStyle('D'.$z)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$z)->getNumberFormat()->setFormatCode('0.0');
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$z,'Last Batch Qty:'.$bqty);
		//print("finished printing part total timing details<p>");
		if($tasum=='')
			{
				$tasum.='D'.$z;
			}else{
			$tasum.='+D'.$z;
			}
		$x++;
		$totalhours+=$tahours;

//print("finished printing part total hours<p>");

}


//print("setting column widths<p>");
foreach(range('A','G') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
//print("finished setting column widths<p>");

$objPHPExcel->getActiveSheet()->setTitle('Simple');

		
// Save Excel 2007 file

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="file.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
*/


?>