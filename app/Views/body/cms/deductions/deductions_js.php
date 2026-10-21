<script type="text/javascript">
	
	$(document).ready(function() {
        
        //var dataTable = $('#deDTable').DataTable({});
        
        $('#deDTable').DataTable({
            "order": [], // disable initial ordering
        });

		$('div#show-details').on('hidden.bs.modal', function (e) {

		    $('body').addClass('modal-open');
		});
	});

	function viewDeduction(vendor_code, store, category_id, date_from, date_to) {
		
		$("#show-deduction").modal({
		    backdrop: 'static', 
		    keyboard: false
		});

		$("#show-deduction").modal("show");
	
        $.ajax({
			type : "POST",	
            url  : "<?php echo site_url('supplier/view_penalty'); ?>",
			data : { vendor_code:vendor_code, store:store, category_id:category_id, date_from:date_from, date_to:date_to },
			beforeSend: function(){

                $("div.show-deduction-details").html('<center><img src="<?php echo base_url('assets/img/gif/loader_seq.gif'); ?>"></center>');
            },
            success : function(data){

                $("div.show-deduction-details").html(data);
            }
        });
	}
	
	$("a.vcodes").click(function() {
	    var id = this.id.split("_");
		var vcode = id[1].trim();		
	
	    $.ajax({	    	
			url  : "<?php echo site_url('view_deductions'); ?>",
            type: 'GET',
            data: { vcode:vcode },   
            success: function (data) { 					
            	$("#div_ded").html(data);
			},
	    });
	});
	

</script>