<script type="text/javascript">
	
	$(document).ready(function() {
		var dataTable = $('#itemDTable').DataTable({});
	});
	
    $("a.vcodes").click(function() {
	    var id = this.id.split("_");
		var vcode = id[1].trim();
	
	    $.ajax({	    	
			url  : "<?php echo site_url('view_items'); ?>",
            type: 'GET',
            data: { vcode:vcode },   
            success: function (data) { 	
            	$("#div_items").html(data);
			},
	    });
	});
</script>