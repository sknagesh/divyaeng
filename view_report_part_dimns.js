$(document).ready(function() {

$('#viewipdimn').validate(); //attach form to validation engine
$('#reptype').hide();
$('#customer').load("get_customer.php"); //load customer list on to div customer

$('#customer').click(function() {     //populate drawing list based on customer
	var custid=$('#Customer_ID').val();
	var url="get_drawing.php?custid="+custid;
	if(custid!='')
	{
	$('#drawing').load(url);
	$('#operation').text(' ');
	$('#ipdimns').text(' ');
	}else{
		$('#drawing').text(' ');
	}
    });


$("#drawing").click(function() {      //populate operation list based on drawing no
	var drawingid=$('#Drawing_ID').val();
	if(drawingid!='')
		{
		var url="get_operations.php?drawingid="+drawingid;
		$('#operation').load(url);
		}else{
		$('#operation').text('');
		}
});

$("#operation").click(function() {     //show inprocess dimensions based on operation no
	var oid=$('#Operation_ID').val();
	var did=$('#Drawing_ID').val();
	if(oid!='')
	{
	var url2='get_open_batch_no.php?drawingid='+did;
	$('#batch').load(url2);
	}
	});

$("#batch").change(function() {      //populate operation list based on drawing no
	var drawingid=$('#Drawing_ID').val();
	var batchid=$('#Batch_ID').val();
	if(batchid!="")
  		{
$('#reptype').show();
		}
		else
		{
			$('#reptype').hide();
		}
	});
$('#reptype').click(function(){
	var rtype=$('#Report_Type').val();
	var batchid=$('#Batch_ID').val();
	var opid=$('#Operation_ID').val();
	if(rtype!=''){
		
			var url="view_report_part_dimns.php?opid="+opid+"&batchid="+batchid+"&reptype="+rtype;
			$('#ipdimns').load(url);
	}
	
	
});

$('#viewipdimn').submit(function(){
if (!$('*[id^="jobno"]').is(':checked')){
	alert("Please Select At Least One job for Report");
	return false;
}else if (!$('*[id^="bno"]').is(':checked')){
	alert("Please Select At Least One Dimension for Report");
	return false;
}else{
	return true;
}
});

});

