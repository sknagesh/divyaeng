$(document).ready(function() {
var maxbqty=10;

$('#bdetails').hide();

$('#batchno').validate(); //attach form to validation engine

$('#customer').load("get_customer.php"); //load customer list on to div customer

$('#customer').click(function() {     //populate drawing list based on customer
	var custid=$('#Customer_ID').val();
	var url="get_drawing.php?custid="+custid;
	if(custid!='')
		{
		$('#drawing').load(url);
		}
    });


$('#drawing').click(function() {     //populate drawing list based on customer
	var drawid=$('#Drawing_ID').val();
	
	if(drawid!='')
	{
		url="get_open_Batch_Nos.php?drawid="+drawid;
		url2="get_inward_material_id.php?drawid="+drawid;
		$('#bdetails').show();
		$('#material').load(url2);
		$('#footer2').load(url);		
	}

    });

$('#Batch_Qty').keyup(function(){
	var qty=$('#Batch_Qty').val();
console.log(maxbqty);	
	
});


$('#autobatch').live("click",function(){

	if($('#autobatch').is(':checked'))
	{
	$('#Batch_Desc').attr("disabled",true);
	}
	else{
		$('#Batch_Desc').attr("disabled",false);
	}
	
		});


});

$("form#batchno").live("submit",function(event) {
	event.preventDefault();
	var $this = $(this);
	
	$.ajax({data: $this.serializeArray(),
   			dataType: "html",
   			type: $this.attr("method"),
   			url: $this.attr("action"),
   			success: function(html) {
								document.getElementById('footer').innerHTML=html;
								$('#batchno')[0].reset();
								$('#bdetails').hide();								
      								}
			});

$("#Batch_Qty").rules("add", {
 max: maxbqty
});






	});

