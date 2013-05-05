$(document).ready(function() {
var maxbqty=0;

$('#bdetails').hide();

$('#batchno').validate({
	
		rules:{
		Batch_Qty:{
			required: true,
			number: true,
			remote: {
				url: "get_max_batch_qty.php",
				type: "post",
				data:{
					Inward_ID: function(){return $('#Inward_ID').val();},
					Batch_Qty: function(){return $('#Batch_Qty').val();}
					}
				}
			}
		},
				messages:{
			
			Batch_Qty:{
				remote:	"Please enter Correct Batch Quantity"
			}
		}
		
	
	
}); 


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
		url2="get_inward_material_id.php?drawid="+drawid;
		$('#material').load(url2);
	
	}

    });

$('#material').click(function(){
		var imid=$('#Inward_ID').val();

if(imid!='')
{
	url="get_open_Batch_Nos.php?imid="+imid;
	$('#bdetails').show();
	$('#footer2').load(url);
}else
{
	$('#bdetails').hide();
}
		
	
	
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





	});






