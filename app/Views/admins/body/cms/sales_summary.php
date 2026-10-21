<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active"><?= ucwords($submenu) ?> </li>
	</ol>
	<h1 class="page-header"> CMS <small> Sales Summary </small></h1>	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4> Sales Summary </h4>
		</div>
		<div class="panel-body">
			<table id="dt_sales_summary" class="table table-bordered table-striped" width="100%">
				<thead>
					<tr>
						<th> ISS No </th>
						<th> Vendor Code </th>
						<th> Vendor Name </th>
						<th> Comm </th>
						<th> Net Sales </th>
						<th> Sales Commission  </th>
						<th> Cutoff Start  </th>
						<th> Cutoff End  </th>
						<th> Posted By  </th>
						<th> Date Posted  </th>
						<th> Action  </th>
					</tr>
				</thead>
			</table>
		</div>
	</div>		
</div>

<div class="modal fade" id="view_sales">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Item Sales Report (Item Details) </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>            
			<div class="modal-body">
				<div id="view_sales_body"> 
				Loading Item Sales...
				</div>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
			</div>            
        </div>
    </div>
</div>

<script>
	function view_sales(sufid) {

		$("div#view_sales").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("div#view_sales").modal("show");

		$.ajax({
			url: "<?php echo site_url('admins/view_item_sales'); ?>",
			type: 'GET',
			data: { sufid: sufid },
			success: function(result) {
				$("#view_sales_body").html(result);
			}
		});
	}
</script>	