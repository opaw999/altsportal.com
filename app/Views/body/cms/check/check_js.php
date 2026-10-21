<script type="text/javascript">
	
	$(document).ready(function() {
		//var dataTable = $('#checkDTable').DataTable({});
		$('#checkDTable').DataTable({
            "order": [], // disable initial ordering
        });
	});
	
	$("a.vcodes").click(function() {
	    var id = this.id.split("_");
		var vcode = id[1].trim();
	
	    $.ajax({	    	
			url  : "<?php echo site_url('view_check'); ?>",
            type: 'GET',
            data: { vcode:vcode },   
            success: function (data) { 	
            	$("#div_check").html(data);
			},
	    });
	});
</script>