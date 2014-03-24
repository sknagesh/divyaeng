<?php
include('dewdb.inc');
include '../Classes/PHPExcel.php';
/** PHPExcel_Writer_Excel2007 */
include '../Classes/PHPExcel/Writer/Excel2007.php';




$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setTitle("Part Timing List");
$objPHPExcel->setActiveSheetIndex(0);

$tasum='';

$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawinglist=$_POST['dlist'];

$dids=explode(',', $drawinglist);


$x=1;
$y=0;
	$totalhours=0;
for($i=0;$i<count($dids)-1;$i++)
{
	$tp=explode('-', $dids[$i]);

	$q1="SELECT Drawing_NO,Component_Name from Component WHERE Drawing_ID='".$tp[0]."';";
	$result1=mysql_query($q1) or die(mysql_error());
	$r1=mysql_fetch_assoc($result1);

	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$x, 'Part:'.$r1['Drawing_NO'].' - '.$r1['Component_Name']);
	$x++;
	$q2="SELECT Setup_Time,ADDTIME(Clamping_Time,Machining_Time) as tt,Operation_Desc,
	($tp[1]*TIME_TO_SEC(ADDTIME(Clamping_Time,Machining_Time))) as Total_Time 
	FROM Operation WHERE Drawing_ID='".$tp[0]."' AND In_Tool_List=1 ORDER BY Operation_Desc ASC;";

	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$x,'Std. Time in Min');
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$x,'Set Up Time');
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$x,'Avg Time in Min');
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$x, 'Operation Description');
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$x,'Total Avg Time in Hours for'.$tp[1].' Nos');
	$x++;
	$y=$x;//store row no for next column of data
	$rr=mysql_query($q2) or die(mysql_error());
	$noofop=mysql_affected_rows();
	$pn=0;
	$thours=0;
	while($row=mysql_fetch_assoc($rr))
	{
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$x,$row['tt']);
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$x, $row['Operation_Desc']);
//	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$x,round($row['Total_Time']/3600,1));
//	$thours+=round($row['Total_Time']/3600,1);
		$x++;
	
	}

////get last batch run details for timing comparision


$q3="SELECT bn.Batch_ID,Qty_In_Batch from Batch_NO as bn 
INNER JOIN BNo_MI_Challans as bmc ON bmc.Batch_ID=bn.Batch_ID
INNER JOIN MI_Drg_Qty as midq ON midq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
WHERE midq.Drawing_ID=$tp[0] ORDER BY Deposit_Date Desc;";

	$r3=mysql_query($q3) or die(mysql_error());
	$nr3=mysql_affected_rows();
	if($nr3!=0)
	{
	$rr1=mysql_fetch_assoc($r3);
	$bid=$rr1['Batch_ID'];
	$bqty=$rr1['Qty_In_Batch'];
	}




$q4="SELECT ope.Operation_Desc,TIME_TO_SEC(ADDTIME(Clamping_Time,Machining_Time)) as tmt,
SUM(CASE WHEN Activity_ID=1 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Production,
SUM(CASE WHEN Activity_ID=2 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Setup,
SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Rework,
SUM(CASE WHEN Activity_ID=9 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS CMM,
SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
WHERE ope.Drawing_ID=$tp[0] AND Batch_ID='$bid' AND In_Tool_List=1 GROUP BY ope.Operation_ID;";

$r4=mysql_query($q4) or die(mysql_error());
$rrr4=mysql_affected_rows();

if($rrr4!=0)
{

$x=$y;
$tahours=0;
	while($row4=mysql_fetch_assoc($r4))
	{
			if($row4['Production']!=''){$p=$row4['Production'];}else{$p='';}
			if($row4['Setup']!=''){$s=$row4['Setup'];}else{$s='';}
			if($row4['Rework']!=''){$rw=min2hm($row4['Rework']);}else{$rw='';}
			if($row4['CMM']!=''){$cmm=min2hm($row4['CMM']);}else{$cmm='';}
			if($row4['Total']!=''){$t=min2hm($row4['Total']);}else{$t='';}
			if($row4['tmt']!=''){$tmt=min2hm($row4['tmt']/60);}else{$tmt='';}

	$w=$y-2;
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$x,round($p/$bqty,2));
	//$objPHPExcel->getActiveSheet()->SetCellValue('F'.$x,round((($p/$bqty)*$tp[1])/60,1));
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$x,'=((C'.$x.'*B'.$w.')+B'.$x.')/60');
	$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getNumberFormat()->setFormatCode('0.0');
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$x,$s);
	$tahours+=(($p/$bqty)*$tp[1])/60;
	$x++;
	}


}



$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000'),
        'size'  => 12,
        'name'  => 'Verdana'
    ));

$z=$y-2;
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$z,$tp[1]);
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
if($tasum=='')
{
$tasum.='D'.$z;
}else{
	$tasum.='+D'.$z;
}
$x++;
$totalhours+=$tahours;
}

$objPHPExcel->getActiveSheet()->SetCellValue('D'.$x,'Total Hours from All Parts');
$objPHPExcel->getActiveSheet()->SetCellValue('E'.$x,'='.$tasum);
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getNumberFormat()->setFormatCode('0.0');







foreach(range('A','G') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}











$objPHPExcel->getActiveSheet()->setTitle('Simple');

		
// Save Excel 2007 file

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="file.xlsx"');

// Write file to the browser
$objWriter->save('php://output');



?>