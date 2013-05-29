$(document).ready(function() {
$('#inputdimn').validate(); //attach form to validation engine
$('#s').hide();
$('#customer').load("get_customer.php"); //load customer list on to div customer

$('#insp').hide();

$(document).on("keyup", '*[id^="obser"]', function(event){//function to check if observed dimn is with in tolerance
	var ok="";
	var eid=$(this).attr("id");
	var p1=eid.indexOf("[")+1;
	var p2=eid.indexOf("]");
	var id=eid.substring(p1,p2);
	console.log("p1="+p1+"p2="+p2+"id="+id);
	var tl='tl['+id+']';
	var tu='tu['+id+']';
	var bd='bd['+id+']';
	var tlow=document.getElementById(tl).value;
	var tup=document.getElementById(tu).value;
	var bdim=document.getElementById(bd).value;
	var edimn=$(this).val();
console.log("tlow="+tlow+"tup="+tup+"bdim="+bdim+"edim="+edimn);

	if(edimn!="")
		{
			var url="checkvalue.php?tlow="+tlow+"&tup="+tup+"&bdim="+bdim+"&edimn="+edimn;
			$.ajax({  //calling php script because js math abilities are worse at best
      					type: "GET",
      					url: url,
      					async:false,
      					success: function(html) {
						ok=html;
													}
    							});
						if(ok=="0"){$(this).css("background-color","red");}else{$(this).css("background-color","#00FF99");}

		}
	});


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



$("#batch").change(function() {      //populate operation list based on drawing no
	var drawingid=$('#Drawing_ID').val();
	var batchid=$('#Batch_ID').val();
	if(batchid!="")
  		{
	$('#ipdimns').empty();
	$('#inspector').load("get_operator.php?insp=1"); //load customer list on to div customer
	$('#insp').show();

		}
		else
		{
			$('#ipdimns').empty();
			$('#insp').hide();

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


$('input[id^="fai"]').click(function() {      //show dimensions based on selection
	var drawingid=$('#Drawing_ID').val();
	var fai=$(this).val();
	var opid=$('#Operation_ID').val();
//console.log(fai);
	var url="get_dimnensions_for_operation.php?opid="+opid+'&fai='+fai;
	$('#ipdimns').load(url);
	$('#s').show();
	});


$("#submit").click(function(event) {

		event.preventDefault();
		$.ajax({
      					data: $('#inputdimn').serializeArray(),
      					type: "POST",
   						url: "job_dimension_observations.php",
      					success: function(html) {

				document.getElementById("footer").innerHTML=html;
//				$('#inputdimn')[0].reset();
					$('#ipdimns').text(' ');
      							}
	});

});



});



$(function () {
	$('#sdate').datetimeEntry({datetimeFormat: 'D-O-Y',altField: '#dbdate', altFormat: 'Y-O-D'});
	
});  
