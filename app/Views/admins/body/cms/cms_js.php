<script type="text/javascript">
	$(document).ready(function() {

		var dt_vendor       = $("table#dt_vendor").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_vendor'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
		});

		var dt_vis = $("table#dt_vis").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_vis'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],			
		});

		var dt_cas          = $("table#dt_cas").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_cas'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],			
		});

		var dt_items        = $("table#dt_items").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_items'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],	
		});

		var dt_po           = $("table#dt_po").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_po'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
		});

		var dt_deduction    = $("table#dt_deduction").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_deduction'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
		});

		var dt_checkdetails = $("table#dt_checkdetails").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_check_details'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
		});

		var dt_checkvoucher = $("table#dt_checkvoucher").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_check_voucher'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
		});

		var dt_checks       = $("table#dt_checks").DataTable({
			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_check_monitoring'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
		});	
		
		var dt_sales_uploaded = $("table#dt_sales_uploaded").DataTable({

			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_sales_uploaded'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
		});
		
		var dt_sales_summary = $("table#dt_sales_summary").DataTable({

			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_sales_summary'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
		});

		var dt_sales  = $("table#dt_sales").DataTable({

			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('admins/fetch_sales'); ?>",
				type: "POST"
			},
			"order": [0, "asc"],
		});

		//$('[data-toggle="tooltip"]').tooltip();

		// $.post("<?php echo site_url('dataTable_script'); ?>", {
		// 	request: "dataTable"
		// }, function(data, status) {
		// 	$("head").append(data);
		// });

		var dt_userAccount = $("table#dt_userAccount").DataTable({

			"destroy": true,
			"ajax": {
				url: "<?php echo site_url('fetch_user_account'); ?>",
				type: "post"
			},
			"columns": [{
					"className": 'details-control',
					"orderable": false,
					"data": null,
					"defaultContent": ''
				},
				{
					"data": "0"
				},
				{
					"data": "1"
				},
				{
					"data": "2"
				},
				{
					"data": "3"
				},
				{
					"className": 'text-center',
					"data": "4"
				},
				{
					"className": 'text-center',
					"data": "5"
				},
				{
					"className": 'text-center',
					"data": "6"
				}
			],
			"order": [
				[1, 'asc']
			]
		});

		// Add event listener for opening and closing details
		$('table#dt_userAccount tbody').on('click', 'td.details-control', function() {

			var tr = $(this).closest('tr');
			var row = dt_userAccount.row(tr);

			if (row.child.isShown()) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			} else {
				// Open this row
				row.child(format(row.data())).show();
				tr.addClass('shown');
			}
		});
				
		$('form#uploadTable').on('submit', function (e) {
    		e.preventDefault();
    
    		var formData = new FormData(this);
    		var table_name = $("[name='table_name']").val();

    		if (table_name == "tbl_sales") {
                urls = "<?php echo site_url('admins/import_csv_direct'); ?>"; 
            } else {
                urls = "<?php echo site_url('admins/import_csv'); ?>";
            }
	
    		$.ajax({
    		    url: urls, // Now it uses the correct URL
    			type: 'POST',
    			data: formData,
    			contentType: false, // Required for file upload
    			processData: false, // Required for file upload
    			beforeSend: function () {
    				$('#uploadResult').html('<span class="text-primary">Uploading...</span>');
    			},
    			success: function (response) {
    				$('#uploadResult').html('<span class="text-success">' + response + '</span>');
    				$('#uploadTable')[0].reset();
    			},
    			error: function (xhr) {
    				$('#uploadResult').html('<span class="text-danger">Upload failed: ' + xhr.responseText + '</span>');
    			}
    		});
			
    	});

	});

</script>