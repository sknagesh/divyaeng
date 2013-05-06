<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$miid=$_GET['miid'];

$query="SELECT Material_Inward_ID,EX_Challan_NO,DATE_FORMAT(EX_Challan_Date,'%d/%m/%Y') as ecd,
				DA_NO,DATE_FORMAT(DA_Date,'%d/%m/%Y') as dad,GP_NO,DATE_FORMAT(GP_Date,'%d/%m/%Y') as gpd,
				Purchase_Ref,DATE_FORMAT(Purchase_Ref_Date,'%d/%m/%Y') as prd,MI_Ch_ID,EX_Challan_Date,
				DA_Date,GP_Date,Purchase_Ref_Date FROM 
				MI_Challans WHERE Mi_Ch_ID='$miid';";


$res = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($res);

//print($query);

$st='';

if($row['ecd']!='00/00/0000'){$ecd=$row['ecd'];}else{$ecd='';}
if($row['dad']!='00/00/0000'){$dad=$row['dad'];}else{$dad='';}
if($row['gpd']!='00/00/0000'){$gpd=$row['gpd'];}else{$gpd='';}
if($row['prd']!='00/00/0000'){$prd=$row['prd'];}else{$prd='';}
$st.='<p>
     <label>Challan Date</label>
     <input id="cdate" name="cdate" size="25" class="required" value="'.$ecd.'"/>
     <input type="hidden" id="cdatedb" name="cdatedb" value="'.$row['EX_Challan_Date'].'"/>
   </p>
   <p>
     <label>Challan Number</label>
     <input id="cno" name="cno" size="25"  class="required" value="'.$row['EX_Challan_NO'].'" />
   </p>
   <p>
     <label>Gatepass Date</label>
     <input id="gpdate" name="gpdate" size="25" class="required" value="'.$gpd.'"/>
     <input type="hidden" id="gpdatedb" name="gpdatedb" value="'.$row['GP_Date'].'"/>
   </p>
   <p>
     <label>Gatepass Number</label>
     <input id="gpno" name="gpno" size="25"  class="required" value="'.$row['GP_NO'].'" />
   </p>

   <p>
     <label>DA Date</label>
     <input id="dadate" name="dadate" size="25" class="required" value="'.$dad.'"/>
     <input type="hidden" id="dadatedb" name="dadatedb" value="'.$row['DA_Date'].'"/>
   </p>
   <p>
     <label>DA Number</label>
     <input id="dano" name="dano" size="25"  class="required" value="'.$row['DA_NO'].'" />
   </p>

   <p>
     <label>Purchase Referance</label>
     <input id="pref" name="pref" size="25"  class="required" value="'.$row['Purchase_Ref'].'" />
   </p>
   <p>
     <label>Purchase Ref Date</label>
     <input id="prdate" name="prdate" size="25" class="required" value="'.$prd.'"/>
     <input type="hidden" id="prdatedb" name="prdatedb" value="'.$row['Purchase_Ref_Date'].'"/>
   </p>';

print($st);

?>