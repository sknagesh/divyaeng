<?php
include('dewdb.inc');
include '../Classes/PHPExcel.php';
include '../Classes/PHPExcel/IOFactory.php';
/** PHPExcel_Writer_Excel2007 */
include '../Classes/PHPExcel/Writer/Excel2007.php';


$uploadDir = '/home/www/divyaeng/pdf/';


$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

if(isSet($_POST['Drawing_ID'])){$drawingid=$_POST['Drawing_ID'];}else{$drawingid='';}
if(isSet($_POST['Enquiry_ID'])){$enquiryid=$_POST['Enquiry_ID'];}else{$enquiryid='';}
if(isSet($_POST['cdatedb'])){$cdatedb=$_POST['cdatedb'];}else{$cdatedb='';}
if(isSet($_POST['cdate'])){$cdate=$_POST['cdate'];}else{$cdate='';}
if(isSet($_POST['pdesc'])){$pdesc=$_POST['pdesc'];}else{$pdesc='';}
if(isSet($_POST['eau'])){$eau=$_POST['eau'];}else{$eau='';}
if(isSet($_POST['bsize'])){$batchsize=$_POST['bsize'];}else{$batchsize='1';}
if(isSet($_POST['nos'])){$noofsettings=$_POST['nos'];}else{$noofsettings='';}
if(isSet($_POST['stime'])){$totalbatchsettingtime=$_POST['stime'];}else{$totalbatchsettingtime='';}
if(isSet($_POST['actime'])){$actualcuttingtime=$_POST['actime'];}else{$actualcuttingtime='';}
if(isSet($_POST['effy'])){$efficiency=$_POST['effy'];}else{$efficiency='';}
if(isSet($_POST['hwork'])){$handwork=$_POST['hwork'];}else{$handwork='';}
if(isSet($_POST['holes'])){$holes=$_POST['holes'];}else{$holes='';}
if(isSet($_POST['packing'])){$packing=$_POST['packing'];}else{$packing='';}
if(isSet($_POST['transport'])){$transportation=$_POST['transport'];}else{$transportation='';}
if(isSet($_POST['cscrap'])){$costofscrap=$_POST['cscrap'];}else{$costofscrap='';}

if($enquiryid!='')
{
	$q2="SELECT * FROM Enquiry_Record WHERE Enquiry_ID='$enquiryid';";
	$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));
	$res2=mysql_fetch_assoc($r2);
	$dno=$res2['Drawing_NO'];
	$compname=$res2['Component_Name'];

}else
if($drawingid!='')
{
	$q2="SELECT *,Customer_Name FROM Component as comp 
	INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID WHERE Drawing_ID='$drawingid';";
	$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));
	$res2=mysql_fetch_assoc($r2);
	$dno=$res2['Drawing_NO'];
	$compname=$res2['Component_Name'];
}

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setTitle("Part Timing List");
$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('B2','Date');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2',$cdate);
		$objPHPExcel->getActiveSheet()->SetCellValue('C3',"Machining Charges for Machining of ".$compname);
        $objPHPExcel->getActiveSheet()->SetCellValue('A5','EAU');
        $objPHPExcel->getActiveSheet()->SetCellValue('B5','Part No');
        $objPHPExcel->getActiveSheet()->SetCellValue('C5','Batch Size');
        $objPHPExcel->getActiveSheet()->SetCellValue('D5','No Of Settings/Batch');
        $objPHPExcel->getActiveSheet()->SetCellValue('E5','Setting Time/Part in Hours');
        $objPHPExcel->getActiveSheet()->SetCellValue('F5','Actual Metal Cutting Time/Part');
        $objPHPExcel->getActiveSheet()->SetCellValue('G5','Cycle Time/Part with Sett. Time');
        $objPHPExcel->getActiveSheet()->SetCellValue('H5','Eff.');
        $objPHPExcel->getActiveSheet()->SetCellValue('I5','Total Mach. Hours with Efficiency');
        $objPHPExcel->getActiveSheet()->SetCellValue('J5','Total Machining Cost @450 MHR');
        $objPHPExcel->getActiveSheet()->SetCellValue('K5','Hand Work');
        $objPHPExcel->getActiveSheet()->SetCellValue('L5','Additional Holes Using Jig');
        $objPHPExcel->getActiveSheet()->SetCellValue('M5','Packing');
        $objPHPExcel->getActiveSheet()->SetCellValue('N5','Transport');
        $objPHPExcel->getActiveSheet()->SetCellValue('O5','HW+Packing+Transport');
        $objPHPExcel->getActiveSheet()->SetCellValue('P5','Cost Of Scrap');
        $objPHPExcel->getActiveSheet()->SetCellValue('Q5','Total Processing Cost');
        $objPHPExcel->getActiveSheet()->SetCellValue('R5','With Profit @10%');
        $objPHPExcel->getActiveSheet()->SetCellValue('S5','Total Cost');



        $objPHPExcel->getActiveSheet()->SetCellValue('A6',$eau);
        $objPHPExcel->getActiveSheet()->SetCellValue('B6',$dno);
        $objPHPExcel->getActiveSheet()->SetCellValue('C6',$batchsize);
        $objPHPExcel->getActiveSheet()->SetCellValue('D6',$noofsettings);
        $objPHPExcel->getActiveSheet()->SetCellValue('E6',$totalbatchsettingtime/60);
        $objPHPExcel->getActiveSheet()->SetCellValue('F6',$actualcuttingtime/60);
        $objPHPExcel->getActiveSheet()->SetCellValue('G6','=E6/C6');
        $objPHPExcel->getActiveSheet()->SetCellValue('H6',$efficiency);
        $objPHPExcel->getActiveSheet()->SetCellValue('I6','=(F6+G6)+(100-H6)/100*(F6+G6)');
        $objPHPExcel->getActiveSheet()->SetCellValue('J6','=I6*575');
        $objPHPExcel->getActiveSheet()->SetCellValue('K6',$handwork);
        $objPHPExcel->getActiveSheet()->SetCellValue('L6',$holes);
        $objPHPExcel->getActiveSheet()->SetCellValue('M6',$packing);
        $objPHPExcel->getActiveSheet()->SetCellValue('N6',$transportation);
        $objPHPExcel->getActiveSheet()->SetCellValue('O6','=K6+N6+M6+L6');
        $objPHPExcel->getActiveSheet()->SetCellValue('P6',$costofscrap);
        $objPHPExcel->getActiveSheet()->SetCellValue('Q6','=J6+O6-P6');
        $objPHPExcel->getActiveSheet()->SetCellValue('R6','=Q6*0.1');
        $objPHPExcel->getActiveSheet()->SetCellValue('S6','=R6+Q6');

        if($pdesc!='')
        {

        	$pdesce=explode('|', $pdesc);
        }
        $i=0;
        $j=10;
        while($i<count($pdesce))
        {
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$j,$pdesce[$i]);	
        $i++;
        $j++;
        }

        
///cell formating
        $objPHPExcel->getActiveSheet()->getStyle('S6')->getNumberFormat()->setFormatCode('0.00');
        $objPHPExcel->getActiveSheet()->getStyle('E6')->getNumberFormat()->setFormatCode('0.00');
        $styleArray = array(
    		'font'  => array(
        	'bold'  => true,
        	'color' => array('rgb' => '000000'),
        	'size'  => 12,
        	'name'  => 'Verdana'
    	));
    	$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('C2:C3')->applyFromArray($styleArray);
        $styleArray = array(
                'borders' => array(
                'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
        $objPHPExcel->getActiveSheet()->getStyle('A5:S6')->applyFromArray($styleArray);


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

//header('Content-type: application/vnd.ms-excel');
//header('Content-Disposition: attachment; filename="file.xlsx"');

$name=$uploadDir.'quote.xls';
$objWriter->save($name);
		
	
	print("<a href=\"pdf/quote.xls\" target=\"_NEW\" title=\"Opens Quote in Excel\">Quote in Excel Format</a>");


?>