<?php
include('dewdb.inc');
require_once('../tcpdf/tcpdf.php');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$dcpath='dcpdfs/';

$custid=$_POST['Customer_ID'];
$dcno=$_POST['dcno'];
$dcdate=$_POST['dcdate'];
$dcdatedb=$_POST['dcdatedb'];
$dctype=$_POST['dctype'];
$gcomm=$_POST['gcomm'];
if(isSet($_POST['cref'])){$cref=$_POST['cref'];}else{$cref='';}
if(isSet($_POST['refdate'])){$refdate=$_POST['refdate'];}else{$refdate='';}
if(isSet($_POST['refdatedb'])){$refdatedb=$_POST['refdatedb'];}else{$refdatedb='';}
if(isSet($_POST['previewok'])){$previewok=$_POST['previewok'];}else{$previewok='';}
$dmode=$_POST['dmode'];
$mlist=$_POST['mlist'];
$drglist=$_POST['drglist'];

$qcdetail="SELECT * FROM Customer WHERE Customer_ID='$custid';";

$rcdetail = mysql_query($qcdetail, $cxn) or die(mysql_error($cxn));
$row=mysql_fetch_assoc($rcdetail);

		$custname=$row['Customer_Name'];
		$addl1=$row["Address_L1"];
		$addl2=$row["Address_L2"];
		$phone=$row["Phone_NO"];
		$tinno=$row['TIN_NO'];
		$exciseno=$row['Excise_NO'];
		$panno=$row['PAN_NO'];

$mlistchunk=explode('<||>', $mlist);

$challanno='';
$quantity='';
$remark='';
$j=0;
for($i=0;$i<count($mlistchunk);$i++)   ///get all challan nos and their quantites and remarks
{
	$cqr_list[$j]=explode('<|>', $mlistchunk[$i]);
	$j++;
}

$dlist=explode('<@>', $drglist);
$dids='';
$dqtys='';
$j=0;
for($i=0;$i<count($dlist);$i++)   ///get all drawing ids and their total quantites
{
	$dq_list[$j]=explode('<|>', $dlist[$i]);
	$j++;
}

//print("<br>ch qty list<br>");
//print_r($cqr_list);

//print("<br>Drg Qty list<br>");
//print_r($dq_list);


class PDF extends TCPDF
{
// Page header
function Header()
{
    // Logo
    $this->SetY(10);
    $this->Image('logo.png',8,10,30);
    // Arial bold 15
    $this->SetFont('helvetica','',22);
    // Move to the right
    $this->Ln(10);
    $this->Cell(80);
    // Title
    $this->Cell(60,0,'Divya Engineering Works (P) Ltd',0,0,'C');
	// Line break
    $this->Ln(10);
	$this->SetFont('helvetica','',11);
	$this->Cell(220,0,'Plot No 31, Hootagalli Industrial Area, Mysore-570018',0,0,'C');
	$this->Ln(6);
	$this->Cell(220,0,'Ph: 0821 2402941, Fax 0821 2402754, email: divyaeng@divyaengineering.com',0,0,'C');
	$this->Ln(4);
	$this->SetFont('Courier','B',11);
	$this->Cell(220,0,'Certified For ISO:9001',0,0,'C');
	$this->Ln(8);
	$this->line(0,45,220,45);
	$this->Ln(0);
	$this->line(0,55,220,55);
	$this->SetFont('helvetica','',16);
	$this->Cell(105,0,'Delivery Challan',0,'0','R');
	$this->Ln(4);
}
function Footer()
{
	
    // Position at 1.5 cm from bottom
    $this->SetY(-35);
	$this->line(0,260,220,260);
    $this->SetFont('helvetica','B',8);
    // Page number
    $this->Cell(200,6,'Commissionerate: Mysore. Division II,S1 & S2,Vinaya Marg, Siddhartha Nagar, Mysore -11 ',0,1,'L');
	$this->Cell(100,6,'Range: Mysore West, Vinaya Marg, Siddartha Nagar, Mysore - 11   C.E.Reg No AAACD6353QXM001   Ser.Tax.Reg. AAACD6353QST002',0,1,'L');
    $this->SetFont('helvetica','B',11);
	$this->Cell(50,6,'TIN: 29570120027',0,'0','L');
	$this->Cell(70,6,'CST: 21261027 Dt. 11-9-1992',0,'0','L');	
	$this->Cell(50,6,'PAN: AAACD6353Q',0,'0','L');
}
}

$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf->AliasNbPages();
$pdf->setAutoPageBreak(1,35);
$pdf->AddPage('P','A4');
$pdf->SetFont('helvetica','B',13);
$pdf->SetY(56);

$pdf->Cell(125,8,$custname,0,0,'L');
$pdf->SetFont('helvetica','B',14);
$pdf->Cell(100,8,"DC NO: $dcno",0,1,'L');
$pdf->SetFont('helvetica','',13);
$pdf->Cell(125,8,$addl1,0,0,'L');$pdf->Cell(100,8,"Date: $dcdate",0,1,'L');
$pdf->Cell(125,8,$addl2,0,0,'L');$pdf->Cell(100,8,"Your Ref: $cref",0,1,'L');
$pdf->Cell(125,8,"Phone No: ".$phone,0,0,'L');$pdf->Cell(100,8,"Date: $refdate",0,1,'L');
$pdf->ln(8);
$pdf->line(0,97,220,97);//line before mode of dispatch
$pdf->Cell(125,8,'',0,0,'L');$pdf->Cell(100,8,"Mode Of Dispatch: $dmode",0,1,'L');
$pdf->Cell(125,8,"",0,0,'L');$pdf->Cell(100,8,"Status: $dctype",0,1,'L');
$pdf->SetFont('helvetica','',10);
$pdf->Cell(150,8,"Please receive the following materials and acknowledge the receipt",0,1,'L');
$pdf->line(0,110,220,110);//line after mode of dispatch
$pdf->line(135,55,135,97);//vertical line b/w address and dc no
$pdf->line(135,72,220,72);//horizontal line b/w dc no and cust ref
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(20,8,"SL NO",0,0,'L');$pdf->Cell(80,8,"Description",0,0,'L');
$pdf->Cell(20,8,"Qty",0,0,'L');$pdf->Cell(50,8,"Remarks",0,0,'L');
$pdf->line(0,120,220,120);//line after slno, desc and remarks
$pdf->ln(6);


//loop to display componet details
$pdf->SetFont('helvetica','',11); //body font
$j=0;
while($j<count($dq_list))
{
	if($dq_list[$j][0]!='')  //only if we have some quantity defined
	{
		$pdf->ln(8);
		$pdf->Cell(20,8,$j+1,0,0,'C');
		$qcdet="SELECT * FROM Component WHERE Drawing_ID='".$dq_list[$j][0]."';";
//print($qcdet);
		$rcdet = mysql_query($qcdet, $cxn) or die(mysql_error($cxn));
		$rowc=mysql_fetch_assoc($rcdet);
		$pdf->MultiCell(80, 8, "$rowc[Component_Name]  $rowc[Drawing_NO]", 0, 'L', 0, 0, '', '', true,0,false,true,8,'M',true);
		$pdf->Cell(20,8,$dq_list[$j][1],0,0,'L');
		$pdf->Cell(75,8,$cqr_list[$j][2],0,0,'L');  //remarks from challan_qty_remarks_array
	}
			$j++;
}


$pdf->setY(200);
$pdf->SetFont('helvetica','',8); //body font
$pdf->MultiCell(80, 8, "Material Received Vide", 0, 'L', 0, 0, '', '', true,0,false,true,8,'M',true);
$j=0;
while($j<count($cqr_list))
{
	if($cqr_list[$j][0]!='')  //only if we have some quantity defined
	{
		$pdf->ln(8);
		$pdf->Cell(20,8,$j+1,0,0,'C');
		$qmdq="SELECT Material_Inward_ID FROM MI_Drg_Qty WHERE MI_Drg_Qty_Id=".$cqr_list[$j][0].";";
		$rmdq = mysql_query($qmdq, $cxn) or die(mysql_error($cxn));
		$remdq=mysql_fetch_assoc($rmdq);
		$miid=$remdq['Material_Inward_ID'];

		$qchdet="SELECT * FROM Material_Inward WHERE Material_Inward_ID='".$miid."';";
//print($qcdet);
		$rchdet = mysql_query($qchdet, $cxn) or die(mysql_error($cxn));
		$rowch=mysql_fetch_assoc($rchdet);
		$pdf->Cell(80,8,$rowch['EX_Challan_NO']." Dated: ".$rowch['EX_Challan_Date'],0,0,'L');
		$pdf->Cell(20,8,"Quantity: ".$cqr_list[$j][1],0,0,'L');

	}
			$j++;
}


$pdf->SetY(-53);
$pdf->SetFont('helvetica','B',11); //note font
$pdf->Cell(200,8,"Consignee's TIN No: ".$tinno,0,1,'L');
$pdf->Cell(200,8,"Note: ".$gcomm,0,0,'L');


if($previewok=='0')
{
	
	$q="INSERT INTO Material_Outward (DC_NO,
									Date,
									Mode_Of_Despatch,
									Dc_Type,
									Comments,
									Cust_Ref,
									Ref_Date)
									
									VALUE('$dcno',
										'$dcdatedb',
										'$dmode',
										'$dctype',
										'',
										'$cref',
										'$refdatedb');";
	$cupq = mysql_query($q, $cxn) or die(mysql_error($cxn));									
	$id=mysql_insert_id();

$j=0;	
while($j<count($cqr_list))
{
	if($cqr_list[$j][0]!='')  //only if we have some quantity defined
	{
		$qmdqd="SELECT Drawing_ID FROM MI_Drg_Qty WHERE MI_Drg_Qty_Id=".$cqr_list[$j][0].";";
		$rmdqd = mysql_query($qmdqd, $cxn) or die(mysql_error($cxn));
		$remdqd=mysql_fetch_assoc($rmdqd);
		$drgid=$remdqd['Drawing_ID'];
		$qmoq="INSERT INTO MO_Drg_Qty (Material_Outward_ID,MI_Drg_Qty_ID,Drawing_ID,Outward_Qty)
								  VALUES('$id','".$cqr_list[$j][0]."','$drgid','".$cqr_list[$j][1]."');";			
//		print('$qmoq');
		$rqmoq = mysql_query($qmoq, $cxn) or die(mysql_error($cxn));

	}
			$j++;
}
	

$j=0;   ///update challan status from open to closed	
while($j<count($cqr_list))
{
	if($cqr_list[$j][0]!='')  //only if we have some quantity defined
	{
		//get qty received and miid for each drawing id dispatched
		$qd="SELECT Material_Qty,Material_Inward_ID,Drawing_ID FROM MI_Drg_Qty WHERE MI_Drg_Qty_ID=".$cqr_list[$j][0].";";
//print("<br>$qd");		
		$rd = mysql_query($qd, $cxn) or die(mysql_error($cxn));
		$resd=mysql_fetch_assoc($rd);
		$miid=$resd['Material_Inward_ID'];
		$miqty=$resd['Material_Qty'];
		$drgid=$resd['Drawing_ID'];
//print("<br>miid=$miid miqty=$miqty  drgid=$drgid");
				//get quantites already dispatched for each drawing
		$query="SELECT sum(Outward_Qty) as oq from MO_Drg_Qty where Drawing_ID='$drgid' and MI_Drg_Qty_ID=".$cqr_list[$j][0].";";
//print("<br>$query");
		$res = mysql_query($query, $cxn) or die(mysql_error($cxn));
		$row=mysql_fetch_assoc($res);
		$oq=$row['oq'];
//print("<br>out qty= $oq");		
		$tq=$miqty-$oq;

		if($tq==0)  //if qty remaining is 0 then set Qty_Open accordingly
		{
		$q3="UPDATE MI_Drg_Qty SET Qty_Open='0' WHERE MI_Drg_Qty_ID=".$cqr_list[$j][0].";";
//print("<br>$q3");
		$res3 = mysql_query($q3, $cxn) or die(mysql_error($cxn));
		}		

		//Now check all items received under challan is dispatched

		$q4="SELECT Drawing_ID FROM MI_Drg_Qty WHERE Qty_Open=1 AND Material_Inward_ID=$miid;";
//print("<br>$q4");
		$res4 = mysql_query($q4, $cxn) or die(mysql_error($cxn));
	
		$opendqty=mysql_affected_rows();

		if($opendqty==0)
		{
		$q5="UPDATE Material_Inward SET Open=0 WHERE Material_Inward_Id='$miid';";
//print("<br>$q5");
		$res5 = mysql_query($q5, $cxn) or die(mysql_error($cxn));
		}


	}
			$j++;
}
	
	
		
	
	
	
	
	$name=$dcpath.$dcno.'.pdf';
	$pdf->Output($name,'F');
	$pdf->Output($name,'D');
//	print("preview ok comitting to database");
	
}else{
		
	$pdf->Output('tempdc.pdf','F');	
//	print("not commiting");

}







?>