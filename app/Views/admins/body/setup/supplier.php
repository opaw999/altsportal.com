<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active"><?= ucwords($submenu) ?> </li>
	</ol>
	<h1 class="page-header">Supplier <small>(Company/Agency)</small></h1>
	<div class="panel panel-default">
		<div class="panel-heading">
			<a href="javascript:;" class="btn btn-primary" onclick="add_details()">Add New Supplier</a>
		</div>
		<div class="panel-body">
			<table id="dt_supplier" class="table table-bordered table-hover align-middle" width="100%">
				<thead>
					<tr>
						<th>Supplier</th>
						<th>Type</th>
						<th>Status</th>
						<th>Date Added</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- #modal-dialog -->
<div class="modal fade" id="supplier_details">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Supplier Details</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<form id="supplier_form" data-parsley-validate="true">
				<div class="modal-body supplier_details"></div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Submit</button>
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>