$(document).ready(function() {
var maxbqty=0;

$('#bdetails').hide();

$('#batchno').validate(); 


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
	{	url="get_open_Batch_Nos.php?drawid="+drawid;
		url2="get_inward_material_id.php?drawid="+drawid;
		$('#material').load(url2);
		$('#footer2').load(url);
		$('#bdetails').show();
	}

    });

$('#autobatch').on("click",function(){

	if($('#autobatch').is(':checked'))
	{
	$('#Batch_Desc').attr("disabled",true);
	}
	else{
		$('#Batch_Desc').attr("disabled",false);
	}
	
		});


});

$("form#batchno").on("submit",function(event) {
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





	});






