$(document).ready(function(){
var lastdiv='';
$('#footer2').hide();
$("#customer").load('get_customer.php');  //load customer list from get_customer.php
$("#viewpart").validate();  //attach validater to form
	$('#customer').click(function(){ ///load drawing list based on customer
		var custid=$('#Customer_ID').val();
		var url='get_drawing.php?custid='+custid;
  		$("#drawing").load(url)
	});

	$('#drawing').click(function(){  //load operation list based on drawing
		var drawingid=$('#Drawing_ID').val();
		var url='get_operations.php?drawingid='+drawingid+"&itl=1";
		if(drawingid!='')
		{
  		$("#operation").load(url)
		var pdfurl="export_tool_list_to_pdf.php?Drawing_ID="+drawingid;		
  		$('#footer2').load(pdfurl);
		$('#footer2').show();
	}
  	});

	$('#operation').click(function(){  
		var opid=$('#Operation_ID').val();  //get selected opid
		var url='show_tool_list_and_details_for_op.php?opid='+opid;  //display already added tools for this operation
		$("#tlist").load(url)
		$('#toolinfo').empty();;
	});
  		


	$(document).on('click', ".tinfo", function(event) {
		var toolid=$(this).val();
		console.log(toolid);
	var tid=$(this).attr('id');
	var p1=tid.indexOf("[")+1;
  	var p2=tid.indexOf("]");
  	var id=tid.substring(p1,p2);
if(lastdiv=='')
{
  lastdiv='#'+id;
}else{
  $(lastdiv).text('');
  lastdiv='#'+id;
}

		var url='get_tool_info.php?toolid='+toolid;
		$('#'+id).load(url);
		$('#'+id).css("background-color","lightgreen");
	
	});



  $(document).on('click', ".detail", function(event) {
    var enqid=$(this).val();
    console.log(enqid);

    var url='get_enquiry_details.php?eid='+enqid;
    $('#'+id).load(url);
  
  });




});
