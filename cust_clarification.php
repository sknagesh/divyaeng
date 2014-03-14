<?php
include('dewdb.inc');
require_once('../tcpdf/tcpdf.php');

$uploadDir = '/home/www/enquiry/';
//print_r($_POST);

$today = date("Y-m-d H-i");
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$opid = $_POST['Operator_ID'];
if(isSet($_POST['Drawing_ID'])){$drawingid=$_POST['Drawing_ID'];}else{$drawingid='';}
if(isSet($_POST['Enquiry_ID'])){$enquiryid=$_POST['Enquiry_ID'];}else{$enquiryid='';}
$cdatedb=$_POST['cdatedb'];
$cdate=$_POST['cdate'];
$pdesc=$_POST['pdesc'];

$oimgfiles=count($_FILES['oimg']['name']);

if($enquiryid!='')
{
	$q2="SELECT * FROM Enquiry_Record WHERE Enquiry_ID='$enquiryid';";
	$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));
	$res2=mysql_fetch_assoc($r2);
	$cname=$res2['Customer_Name'];
	$dno=$res2['Drawing_NO'];
	$compname=$res2['Component_Name'];

}else
if($drawingid!='')
{
	$q2="SELECT *,Customer_Name FROM Component as comp 
	INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID WHERE Drawing_ID='$drawingid';";
	$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));
	$res2=mysql_fetch_assoc($r2);
	$cname=$res2['Customer_Name'];
	$dno=$res2['Drawing_NO'];
	$compname=$res2['Component_Name'];
}

$q3="SELECT * FROM Operator WHERE Operator_ID='$opid';";
	$r3 = mysql_query($q3, $cxn) or die(mysql_error($cxn));
	$res3=mysql_fetch_assoc($r3);
	$rname=$res3['Operator_Name'];



class PDF_SKN extends TCPDF {
// Page header
function Header()
{
	$custname=$GLOBALS['cname'];
	$partno=$GLOBALS['dno'];
	$partname=$GLOBALS['compname'];
	$rdate=$GLOBALS['cdate'];
	$rname=$GLOBALS['rname'];


    
    $this->SetFont('helvetica','',12);
    $this->Cell(80,18,'Divya Engineering Works (P) Ltd, Mysore',1,0,'C');
	$this->Cell(110,18,'Clarification Request',1,0,'C');
	$this->SetFont('helvetica','', 10);
	$this->Cell(85,6,'RECORD REF: DEW/QA/R/07','T R',2,'L');
	$this->Cell(85,6,'DATE: 01-07-2013','R',2,'L');
	$this->Cell(85,6,'REV NO: 00','B R',0,'L');
	$this->ln();
    $this->Cell(190,6,'Customer: '.$custname,'L R',0,'L');
    $this->Cell(85,6,'DATE: '.$rdate,'L B R',1,'L');
    $this->Cell(190,6,'Drg No/Rev No: '.$partno,'L B R',0,'L');
    $this->Cell(85,6,"Requested By: ".$rname,'B R',0,'L');

	}

function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-32);
	// Arial italic 8
    $this->SetFont('helvetica','',16);
	$this->Cell(140,10,"Verified By: U.S",'0',1,'L');
	
    // Page number
    $this->SetY(-10);
    $this->SetFont('helvetica','',6);
	$this->Cell(0,10,'Page'.$this->getAliasNumPage().'/'.$this->getAliasNbPages(),0,0,'C');
}
}


$pdf = new PDF_SKN(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf->AliasNbPages();
$pdf->setTopMargin(10);
$pdf->setAutoPageBreak(0,35);
$pdf->AddPage('L','A4');
$pdf->SetFont('helvetica','',10);

		$pdf->SetY(30);
		$pdf->MultiCell(200, 18,'Problem Statement:'.$pdesc, 0, 'L', 0, 0, '', '', true,0,false,true,10,'M',true);				

/*

$q4="SELECT CC_Image_Path FROM CC_Image WHERE Clarification_ID='$iid';";
$res4=mysql_query($q4) or die(mysql_error());
$r=mysql_affected_rows();
if($r>0)
{
	$n=0;
while($row4=mysql_fetch_assoc($res4))
{
if($n!=0)
{
$pdf->AddPage();
}
$pdf->setJPEGQuality(75);
$pdf->Image($uploadDir.$row4['CC_Image_Path'], 10, 40, 280, 170, '', '', '', true, 150);
$n++;
}

}

*/



if($oimgfiles!=0)
{
	$n=0;
	foreach ($_FILES['oimg']['name'] as $key => $name) 
	{
	
		if($_FILES['oimg']['name'][$key]!='')
		{
		$drgfileName = $today.$drawingid.$enquiryid."-".$_FILES['oimg']['name'][$key];
		$drgtmpName = $_FILES['oimg']['tmp_name'][$key];
		$drgfileSize = $_FILES['oimg']['size'][$key];
		$drgfileType = $_FILES['oimg']['type'][$key];
		$drgfilePath = $uploadDir . $drgfileName;

			if($n!=0)
			{
			$pdf->AddPage();
			}
		$pdf->setJPEGQuality(75);
		$pdf->Image($_FILES['oimg']['tmp_name'][$key], 10, 40, 280, 170, '', '', '', true, 150);
		$n++;
		}
	}

}





$td=strtotime("H-i-s");
$pdfname=$td.$enquiryid.$drawingid.'.pdf';
$pdfsname=$uploadDir.$pdfname;
//	$pdf->Output($pdfname,'I');
 	$pdf->Output($pdfsname,'F');

$q1="INSERT INTO Cust_Clarification (Drawing_ID,
									Enquiry_ID,
									Date_OF_Request,
									Operator_ID,
									Problem_Statement,
									PDF_Path)
								VALUES('$drawingid',
										'$enquiryid',
										'$cdatedb',
										'$opid',
										'$pdesc',
										'$pdfname');";
$r1 = mysql_query($q1, $cxn) or die(mysql_error($cxn));

		$ppath='/enquiry/'.$pdfname;
	print("<a class=\"pdf\" href=\"$ppath\" target=\"_NEW\" title=\"Opens PDF in a new TAB\">Clarification Request  ///out put file name is $pdfsname</a>");
?>