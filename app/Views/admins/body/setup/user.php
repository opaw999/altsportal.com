<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active">User (Admin Account) </li>
	</ol>
	<h1 class="page-header">User <small>(Admin Account)</small></h1>
	<div class="panel panel-default">
		<div class="panel-heading">
			<a href="javascript:;" class="btn btn-primary add_user_btn">Create Admin User</a>
			<!-- <button class="btn btn-green ms-2 generate_excel"><i class="fa fa-file-excel"></i> Generate Excel</button> -->
		</div>
		<div class="panel-body table-responsive">
			<table id="dt_users" class="table table-bordered table-hover align-middle" width="100%">
				<thead>
					<tr>
						<th>Fullname</th>
						<th>Username</th>
						<th>Server</th>
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
<div class="modal fade" id="supplier_account">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Supplier Account Details</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<form id="consignor_account_form" autocomplete="off" data-parsley-validate="true">
				<div class="modal-body supplier_account"> </div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Submit</button>
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="add_adminUser">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Admin User</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<form id="create_admin_user" autocomplete="off" data-parsley-validate="true">
				<div class="modal-body add_adminUser"></div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Submit</button>
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="update_adminUser">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Update</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<form id="update_admin_user" autocomplete="off" data-parsley-validate="true">
				<div class="modal-body update_adminUser"></div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary update_adminUser">Submit</button>
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>