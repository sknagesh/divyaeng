$(document).ready(function(){
$("#customer").load('get_customer.php');  //load customer list from get_customer.php
$("#viewpart").validate();  //attach validater to form
	$('#customer').click(function(){ ///load drawing list based on customer
		var custid=$('#Customer_ID').val();
		var url='get_drawing.php?custid='+custid;
		$('#drawing').load(url);
});



	$('#drawing').click(function(){  //load operation list based on drawing
		var drawingid=$('#Drawing_ID').val();

		if(drawingid!='')
		{
		var purl='get_part_details.php?drawingid='+drawingid;
		$('#footer').load(purl);
		var pdrw='show_part_preview.php?drawingid='+drawingid;
		$('#pdrawing').load(pdrw);
		var ourl="get_op_details_for_part.php?drawingid="+drawingid;
		$('#operation').load(ourl);
		}	
  	});






	$(document).on('click', "a", function(event) {
		event.preventDefault();
		window.open(this.href);
		 		
		});

});
