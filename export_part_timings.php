<?php
ini_set("memory_limit",-1);
include('dewdb.inc');
include '../Classes/PHPExcel.php';
include '../Classes/PHPExcel/IOFactory.php';
/** PHPExcel_Writer_Excel2007 */
include '../Classes/PHPExcel/Writer/Excel2007.php';
$uploadDir = '/home/www/temp/';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setTitle("Part Timing List");
$objPHPExcel->setActiveSheetIndex(0);

$tasum='';

$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawinglist=$_POST['dlist'];

if((isSet($_FILES['drg']['name']))&&$_FILES['drg']['name']!='')
{
	$drgfileName = $_FILES['drg']['name'];
	$drgtmpName = $_FILES['drg']['tmp_name'];
	$drgfileSize = $_FILES['drg']['size'];
	$drgfileType = $_FILES['drg']['type'];
	$drgfilePath = $uploadDir . $drgfileName;
	chmod($drgfilePath, 777);
	 if(file_exists($drgfilePath))
                {
//						print("deleting old file...<br>");
                        unlink($drgfilePath);
                }
	$result = move_uploaded_file($drgtmpName, $drgfilePath);
	if (!$result) {
						echo "<br>Error uploading Drawing $drgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$drgfileName = addslashes($drgfileName);
						$drgfilePath = addslashes($drgfilePath);
						}

try {
    $objPHPExcelRead = PHPExcel_IOFactory::load($drgfilePath);
//    print("successfully reading excel file $drgfileName<p>");
} catch (Exception $e) {
    die("Error loading file: ".$e->getMessage()."<br />\n");
}

}


$sheet = $objPHPExcelRead->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
//print("Highest row=$highestRow and column=$highestColumn");

$x=1;
$y=0;
$totalhours=0;

for ($rows = 2; $rows <= $highestRow; $rows++)
{
    $drgno = $sheet->getCell('A'.$rows)->getValue();  //get sustomer drawing no in column A
    $drgqty = $sheet->getCell('B'.$rows)->getValue();  //get quantity in column B
//    print("$drgno:$drgqty<p>");

	if($drgno!='')
	{
		$q6="SELECT Drawing_ID FROM Component WHERE Cust_Drawing_NO='$drgno';";
		$result6=mysql_query($q6) or die(mysql_error());
		$r6=mysql_fetch_assoc($result6);
		$did=$r6['Drawing_ID'];
//print("Draing id=$did<p>");
		$q1="SELECT Drawing_NO,Component_Name from Component WHERE Drawing_ID='".$did."';";
		$result1=mysql_query($q1) or die(mysql_error());
		$r1=mysql_fetch_assoc($result1);
//print("printing component information<p>");
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$x, 'Part:'.$r1['Drawing_NO'].' - '.$r1['Component_Name']);
//print("finished printing comp information<p>");
		$x++;
		$q2="SELECT Setup_Time,ADDTIME(Clamping_Time,Machining_Time) as tt,Operation_Desc,
		($drgqty*TIME_TO_SEC(ADDTIME(Clamping_Time,Machining_Time))) as Total_Time 
		FROM Operation WHERE Drawing_ID='".$did."' AND In_Tool_List=1 ORDER BY Operation_Desc ASC;";
//print("printing headers<p>");
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$x,'Std. Time in Min');
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$x,'Set Up Time');
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$x,'Avg Time in Min');
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$x, 'Operation Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$x,'Total Avg Time in Hours for'.$drgqty.' Nos');
//print("finished printing headers<p>");
		$x++;
		$y=$x;//store row no for next column of data
		$rr=mysql_query($q2) or die(mysql_error());
		$noofop=mysql_affected_rows();
		$pn=0;
		$thours=0;
//print("printing timing details<p>");
		while($row=mysql_fetch_assoc($rr))
			{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$x,$row['tt']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$x, $row['Operation_Desc']);
			$x++;
			}
//print("finished printing timing details<p>");
////get last batch run details for timing comparision


		$q3="SELECT bn.Batch_ID,Qty_In_Batch from Batch_NO as bn 
		INNER JOIN BNo_MI_Challans as bmc ON bmc.Batch_ID=bn.Batch_ID
		INNER JOIN MI_Drg_Qty as midq ON midq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		WHERE midq.Drawing_ID=$did ORDER BY Deposit_Date Desc;";
			//print("$q3");
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
		SUM(CASE WHEN Activity_ID=16 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Proving,
		SUM(CASE WHEN Activity_ID=3 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS Rework,
		SUM(CASE WHEN Activity_ID=9 THEN TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time) END) AS CMM,
		SUM(TIMESTAMPDIFF(minute,Start_Date_Time,End_Date_Time)) AS Total From ActivityLog as actl
		INNER JOIN Production as prod ON prod.Activity_Log_ID=actl.Activity_Log_ID
		INNER JOIN Operation as ope On ope.Operation_ID=prod.Operation_ID
		INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
		INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID 
		WHERE ope.Drawing_ID=$did AND Batch_ID='$bid' AND In_Tool_List=1 GROUP BY ope.Operation_ID;";

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





	}

//print("printing part total hours <p>");
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$x,'Total Hours from All Parts');
$objPHPExcel->getActiveSheet()->SetCellValue('E'.$x,'='.$tasum);
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getNumberFormat()->setFormatCode('0.0');


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



?>